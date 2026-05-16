import type { CollectionDetailModel } from '#shared/types/collection'
import { AppRoutes } from '#shared/router/routes'
import { buildWebPageSchema } from '#shared/seo/builders/webpage.schema'
import { buildItemListSchema } from '#shared/seo/builders/item-list.schema'
import type { MediaShortModel } from '#shared/types/media'
import { buildItemTvSeriesSchema } from '#shared/seo/builders/tv-series.schema'
import { buildItemMovieSchema } from '#shared/seo/builders/movie.schema'
import type { BuildBreadcrumbItemSchemaInput } from '#shared/seo/builders/breadcrumb.schema'

export interface UseCollectionSeoOptions {
  collection: MaybeRefOrGetter<MaybeNullish<CollectionDetailModel>>
  page: MaybeRefOrGetter<number>
  totalPages: MaybeRefOrGetter<number>
  limit: MaybeRefOrGetter<number>
  media: MaybeRefOrGetter<MaybeNullish<MediaShortModel[]>>
}

export const useCollectionDetailSeo = (options: UseCollectionSeoOptions) => {
  const config = useRuntimeConfig()
  const appUrl = config.public.appUrl

  const collection = computed(() => toValue(options.collection))
  const media = computed(() => toValue(options.media))
  const page = computed(() => toValue(options.page))
  const totalPages = computed(() => toValue(options.totalPages))
  const limit = computed(() => toValue(options.limit))

  if (!collection.value || !media.value) {
    return
  }

  const canonical = computed<string>(() => {
    if (page.value < 2) {
      return `${appUrl}${AppRoutes.collectionModel(collection.value!)}`
    }

    return `${appUrl}${AppRoutes.collectionPageModel(collection.value!, page.value)}`
  })

  const breadcrumb = computed<BuildBreadcrumbItemSchemaInput[]>(() => {
    const result: BuildBreadcrumbItemSchemaInput[] = [
      {
        name: 'Коллекции',
        path: AppRoutes.collections()
      },
      {
        name: collection.value!.title,
        path: AppRoutes.collectionModel(collection.value!)
      }
    ]

    if (page.value > 1) {
      result.push({
        name: `Страница ${page.value}`,
        path: AppRoutes.collectionPageModel(collection.value!, page.value)
      })
    }

    return result
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

  const title = computed(() => {
    const collectionTitle = collection.value!.title
    const brand = config.public.appShortTitle

    if (page.value > 1) {
      return `${collectionTitle} — подборка фильмов и сериалов, страница ${page.value} — ${brand}`
    }

    return `${collectionTitle} — подборка фильмов и сериалов — ${brand}`
  })

  const description = computed(() => {
    const collectionDescription = collection.value!.description

    if (page.value > 1) {
      return `${collectionDescription} Ещё больше фильмов и сериалов из этой подборки на странице ${page.value}.`
    }

    return `${collectionDescription} Смотрите подборку онлайн и находите новые фильмы и сериалы для просмотра.`
  })

  const schemas = computed<object[]>(() => {
    const result: object[] = [
      buildWebPageSchema({
        type: 'CollectionPage',
        appUrl,
        canonicalUrl: canonical.value,
        title: title.value,
        description: description.value,
        mainEntity: `${canonical.value}#itemlist`
      }),
      buildItemListSchema({
        canonicalUrl: canonical.value,
        name: title.value,
        page: page.value,
        limit: limit.value,
        items: media.value!.map((item) => ({
          url: `${appUrl}${AppRoutes.watchModel(item)}`,
          item: buildMediaItemSchema(item)
        }))
      })
    ]

    return result
  })

  const buildMediaItemSchema = (media: MediaShortModel) => {
    if (isSeries(media.type)) {
      return buildItemTvSeriesSchema({ appUrl, media })
    }

    if (isMovie(media.type)) {
      return buildItemMovieSchema({ appUrl, media })
    }

    return {}
  }

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

  useJsonLd({
    canonicalUrl: canonical,
    breadcrumb,
    schemas
  })
}
