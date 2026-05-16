import { AppRoutes } from '#shared/router/routes'
import { useJsonLd } from '~/composables/useJsonLd'
import type { BuildBreadcrumbItemSchemaInput } from '#shared/seo/builders/breadcrumb.schema'
import type { MediaListModel } from '#shared/types/media'
import { buildWebPageSchema } from '#shared/seo/builders/webpage.schema'
import { buildItemListSchema } from '#shared/seo/builders/item-list.schema'
import { buildItemTvSeriesSchema } from '#shared/seo/builders/tv-series.schema'

export interface UseSerialySeoOptions {
  page: MaybeRefOrGetter<number>
  hasFilters: MaybeRefOrGetter<boolean>
  limit: MaybeRefOrGetter<number>
  mediaList: MaybeRefOrGetter<MaybeNullish<MediaListModel>>
}

export const useSerialySeo = (options: UseSerialySeoOptions) => {
  const config = useRuntimeConfig()
  const appUrl = config.public.appUrl

  const page = computed(() => toValue(options.page))
  const hasFilters = computed(() => toValue(options.hasFilters))
  const limit = computed(() => toValue(options.limit))
  const mediaList = computed(() => toValue(options.mediaList))

  const title = computed<string>(() => {
    if (page.value === 1) {
      return `Смотреть сериалы онлайн бесплатно в HD и Full HD на ${config.public.appShortTitle}`
    }

    return `Смотреть сериалы онлайн бесплатно в HD и Full HD на ${config.public.appShortTitle} - страница ${page.value}`
  })

  const description = computed<string>(() => {
    if (page.value === 1) {
      return `Смотрите сериалы онлайн бесплатно в хорошем качестве HD и Full HD без регистрации. Новые серии, популярные сериалы, дорамы, детективы, триллеры, комедии, фантастика и другие жанры на ${config.public.appShortTitle}.`
    }

    return `Смотрите сериалы онлайн бесплатно в хорошем качестве HD и Full HD без регистрации. Новые серии, популярные сериалы, дорамы, детективы, триллеры, комедии, фантастика и другие жанры на ${config.public.appShortTitle}. Страница ${page.value}.`
  })

  const canonicalUrl = computed<string>(() => {
    if (page.value === 1) {
      return `${appUrl}${AppRoutes.serialy()}`
    }

    return `${appUrl}${AppRoutes.serialyPage(page.value)}`
  })

  const breadcrumb = computed<BuildBreadcrumbItemSchemaInput[]>(() => [
    { name: 'Сериалы', path: AppRoutes.serialy() },
    {
      name: `Страница ${page.value}`,
      path: AppRoutes.serialyPage(page.value),
      hidden: page.value <= 1
    }
  ])

  const schemas = computed<object[]>(() => [
    buildWebPageSchema({
      type: 'CollectionPage',
      appUrl: config.public.appUrl,
      canonicalUrl: canonicalUrl.value,
      title: title.value,
      description: description.value,
      action: {
        type: 'ReadAction'
      },
      mainEntity: `${canonicalUrl.value}#itemlist`
    }),
    buildItemListSchema({
      canonicalUrl: canonicalUrl.value,
      page: page.value,
      limit: limit.value,
      items: (mediaList.value?.data ?? []).map((item) => ({
        url: `${appUrl}${AppRoutes.watchModel(item)}`,
        item: buildItemTvSeriesSchema({
          appUrl,
          media: item
        })
      }))
    })
  ])

  useJsonLd({
    canonicalUrl,
    schemas,
    breadcrumb
  })

  useHead(() => ({
    meta: [...(hasFilters.value ? [{ name: 'robots', content: 'noindex,follow' }] : [])],
    link: [{ rel: 'canonical', href: canonicalUrl.value }]
  }))

  useSeoMeta({
    title: () => title.value,
    description: () => description.value
  })
}
