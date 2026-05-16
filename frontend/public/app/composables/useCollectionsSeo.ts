import { AppRoutes } from '#shared/router/routes'
import { useJsonLd } from '~/composables/useJsonLd'
import type { BuildBreadcrumbItemSchemaInput } from '#shared/seo/builders/breadcrumb.schema'
import { buildWebPageSchema } from '#shared/seo/builders/webpage.schema'

export interface UseCollectionsSeoOptions {
  page: MaybeRefOrGetter<number>
  totalPages: MaybeRefOrGetter<number>
}

export const useCollectionsSeo = (options: UseCollectionsSeoOptions) => {
  const config = useRuntimeConfig()
  const appUrl = config.public.appUrl

  const page = computed(() => toValue(options.page))
  const totalPages = computed(() => toValue(options.totalPages))
  const canonical = computed(() => {
    if (page.value < 2) {
      return `${appUrl}${AppRoutes.collections()}`
    }

    return `${appUrl}${AppRoutes.collectionsPage(page.value)}`
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
            ? `${appUrl}${AppRoutes.collections()}`
            : `${appUrl}${AppRoutes.collectionsPage(page.value - 1)}`
      })
    }

    if (page.value < totalPages.value) {
      result.push({
        rel: 'next',
        href: `${appUrl}${AppRoutes.collectionsPage(page.value + 1)}`
      })
    }

    return result
  })

  const title = computed<string>(() => {
    if (page.value > 1) {
      return `Коллекции фильмов и сериалов — страница ${page.value} — ${config.public.appShortTitle}`
    }

    return `Коллекции фильмов и сериалов для любого настроения — ${config.public.appShortTitle}`
  })

  const description = computed<string>(() => {
    if (page.value > 1) {
      return `Ещё больше подборок фильмов и сериалов для вечернего просмотра, выходных и любого настроения. Страница ${page.value}.`
    }

    return 'Подборки фильмов и сериалов для любого настроения: от напряжённых триллеров до лёгких комедий. Быстро найдите, что посмотреть онлайн.'
  })

  const breadcrumb = computed<BuildBreadcrumbItemSchemaInput[]>(() => {
    const result = [
      {
        name: 'Коллекции',
        path: AppRoutes.collections()
      }
    ]

    if (page.value > 1) {
      result.push({
        name: `Страница ${page.value}`,
        path: AppRoutes.collectionsPage(page.value)
      })
    }

    return result
  })

  const schemas = computed<object[]>(() => [
    buildWebPageSchema({
      appUrl,
      type: 'CollectionPage',
      canonicalUrl: canonical.value,
      title: title.value,
      description: description.value
    })
  ])

  useHead(() => ({
    link: links.value
  }))

  useSeoMeta({
    title: () => title.value,
    description: () => description.value,
    ogTitle: () => title.value,
    ogDescription: () => description.value,
    ogType: () => 'website',
    robots: () => 'index, follow'
  })

  useJsonLd({
    canonicalUrl: canonical,
    breadcrumb,
    schemas
  })
}
