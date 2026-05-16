import type { CollectionDetailModel } from '#shared/types/collection'
import { AppRoutes } from '#shared/router/routes'

export interface UseCollectionSeoOptions {
  collection: MaybeRefOrGetter<MaybeNullish<CollectionDetailModel>>
  page: MaybeRefOrGetter<number>
  totalPages: MaybeRefOrGetter<number>
}

export const useCollectionDetailSeo = (options: UseCollectionSeoOptions) => {
  const config = useRuntimeConfig()
  const appUrl = config.public.appUrl

  const collection = computed(() => toValue(options.collection))
  const page = computed(() => toValue(options.page))
  const totalPages = computed(() => toValue(options.totalPages))

  if (!collection.value) {
    return
  }

  const canonical = computed<string>(() => {
    if (page.value < 2) {
      return `${appUrl}${AppRoutes.collectionModel(collection.value!)}`
    }

    return `${appUrl}${AppRoutes.collectionPageModel(collection.value!, page.value)}`
  })

  const links = computed(() => {
    const result = [
      {
        rel: 'canonical',
        href: canonical.value
      }
    ]

    if (page.value > 1) {
      result.push({
        rel: 'prev',
        href:
          page.value === 2
            ? `${appUrl}${AppRoutes.collectionModel(collection.value!)}`
            : `${appUrl}${AppRoutes.collectionPageModel(collection.value!, page.value - 1)}`
      })
    }

    if (page.value < totalPages.value) {
      result.push({
        rel: 'next',
        href: `${appUrl}${AppRoutes.collectionPageModel(collection.value!, page.value + 1)}`
      })
    }

    return result
  })

  const title = computed<string>(() => {
    if (page.value > 1) {
      return `${collection.value!.title} — страница ${page.value}`
    }

    return `${collection.value!.title} — смотреть онлайн — ${config.public.appShortTitle}`
  })

  const description = computed<string>(() => {
    if (page.value > 1) {
      return `${collection.value!.description} Страница ${page.value}.`
    }

    return collection.value!.description
  })

  useHead(() => ({
    link: links.value
  }))

  useSeoMeta({
    title: () => title.value,
    description: () => description.value,
    ogTitle: () => title.value,
    ogDescription: () => description.value,
    ogType: 'website',
    robots: 'index, follow'
  })
}
