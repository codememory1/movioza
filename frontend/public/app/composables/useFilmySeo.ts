import type { MediaListModel } from '#shared/types/media'
import { AppRoutes } from '#shared/router/routes'
import { useJsonLd } from '~/composables/useJsonLd'
import type { BuildBreadcrumbItemSchemaInput } from '#shared/seo/builders/breadcrumb.schema'
import { buildWebPageSchema } from '#shared/seo/builders/webpage.schema'
import { buildItemListSchema } from '#shared/seo/builders/item-list.schema'
import { buildItemMovieSchema } from '#shared/seo/builders/movie.schema'

export interface UseFilmySeoOptions {
  page: MaybeRefOrGetter<number>
  hasFilters: MaybeRefOrGetter<boolean>
  limit: MaybeRefOrGetter<number>
  mediaList: MaybeRefOrGetter<MaybeNullish<MediaListModel>>
}

export const useFilmySeo = (options: UseFilmySeoOptions) => {
  const config = useRuntimeConfig()
  const appUrl = config.public.appUrl

  const page = computed(() => toValue(options.page))
  const hasFilters = computed(() => toValue(options.hasFilters))
  const limit = computed(() => toValue(options.limit))
  const mediaList = computed(() => toValue(options.mediaList))

  const title = computed<string>(() => {
    if (page.value === 1) {
      return `Смотреть фильмы онлайн бесплатно в HD и Full HD на ${config.public.appShortTitle}`
    }

    return `Смотреть фильмы онлайн бесплатно в HD и Full HD на ${config.public.appShortTitle} - страница ${page.value}`
  })
  const description = computed<string>(() => {
    if (page.value === 1) {
      return `Смотрите фильмы онлайн бесплатно в хорошем качестве HD и Full HD без регистрации. Новинки кино, популярные фильмы, боевики, драмы, комедии, ужасы, фантастика и другие жанры на ${config.public.appShortTitle}.`
    }

    return `Смотрите фильмы онлайн бесплатно в хорошем качестве HD и Full HD без регистрации. Новинки кино, популярные фильмы, боевики, драмы, комедии, ужасы, фантастика и другие жанры на ${config.public.appShortTitle}. Страница ${page.value}.`
  })

  const canonicalUrl = computed<string>(() => {
    if (page.value === 1) {
      return `${config.public.appUrl}${AppRoutes.filmy()}`
    }

    return `${config.public.appUrl}${AppRoutes.filmyPage(page.value)}`
  })

  const breadcrumb = computed<BuildBreadcrumbItemSchemaInput[]>(() => [
    { name: 'Фильмы', path: AppRoutes.filmy() },
    {
      name: `Страница ${page.value}`,
      path: AppRoutes.filmyPage(page.value),
      hidden: page.value <= 1
    }
  ])

  const schemas = computed<object[]>(() => [
    buildWebPageSchema({
      type: 'CollectionPage',
      appUrl,
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
        item: buildItemMovieSchema({
          appUrl,
          media: item
        })
      }))
    })
  ])

  useJsonLd({
    canonicalUrl,
    breadcrumb,
    schemas
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
