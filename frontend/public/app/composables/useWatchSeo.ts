import type { EpisodeModel, MediaDetailModel, SeasonModel } from '#shared/types/media'
import { AppRoutes } from '#shared/router/routes'
import type { BuildBreadcrumbItemSchemaInput } from '#shared/seo/builders/breadcrumb.schema'
import { buildWebPageSchema } from '#shared/seo/builders/webpage.schema'
import { buildDetailMovieSchema } from '#shared/seo/builders/movie.schema'
import { useRoute } from '#imports'
import {
  buildBaseTvSeriesSchema,
  buildDetailTvSeriesSchema
} from '#shared/seo/builders/tv-series.schema'
import {
  buildBaseTvSeasonSchema,
  buildDetailTvSeasonSchema
} from '#shared/seo/builders/tv-season.schema'
import { buildDetailTvEpisodeSchema } from '#shared/seo/builders/tv-episode.schema'

export interface UseWatchSeoOptions {
  media: MaybeRefOrGetter<MaybeNullish<MediaDetailModel>>
  season: MaybeRefOrGetter<MaybeNullish<SeasonModel>>
  episode: MaybeRefOrGetter<MaybeNullish<EpisodeModel>>
  routeSeasonNumber: MaybeRefOrGetter<MaybeNullish<number>>
  routeEpisodeNumber: MaybeRefOrGetter<MaybeNullish<number>>
}

export const useWatchSeo = (options: UseWatchSeoOptions) => {
  const config = useRuntimeConfig()
  const route = useRoute()
  const appUrl = config.public.appUrl

  const media = computed(() => toValue(options.media))
  const season = computed(() => toValue(options.season))
  const episode = computed(() => toValue(options.episode))
  const routeSeasonNumber = computed(() => toValue(options.routeSeasonNumber))
  const routeEpisodeNumber = computed(() => toValue(options.routeEpisodeNumber))

  if (!media.value) {
    return
  }

  const title = computed<string>(() => {
    const base = `Смотреть ${media.value!.title} (${media.value!.releasedAt.getFullYear()})`

    if (routeEpisodeNumber.value) {
      return `${base} ${routeSeasonNumber.value} сезон ${routeEpisodeNumber.value} серия онлайн в хорошем качестве`
    }

    if (routeSeasonNumber.value) {
      return `${base} — ${routeSeasonNumber.value} сезон онлайн в хорошем качестве бесплатно`
    }

    return `${base} онлайн в хорошем качестве бесплатно`
  })

  const description = computed<string>(() => {
    return episode.value?.description ?? season.value?.description ?? media.value!.description
  })

  const canonicalUrl = computed<string>(() => {
    if (routeEpisodeNumber.value) {
      return `${appUrl}${AppRoutes.watchEpisodeModel(media.value!, season.value!, episode.value!)}`
    }

    if (routeSeasonNumber.value) {
      return `${appUrl}${AppRoutes.watchSeasonModel(media.value!, season.value!)}`
    }

    return `${appUrl}${AppRoutes.watchModel(media.value!)}`
  })

  const ogType = computed(() => {
    if (routeEpisodeNumber.value) {
      return 'video.episode'
    }

    if (routeSeasonNumber.value || (media.value && isSeries(media.value.type))) {
      return 'video.tv_show'
    }

    return 'video.movie'
  })

  const breadcrumb = computed<BuildBreadcrumbItemSchemaInput[]>(() => {
    const items: BuildBreadcrumbItemSchemaInput[] = []

    if (isMovie(media.value!.type)) {
      items.push({ name: 'Фильмы', path: AppRoutes.filmy() })
    }

    if (isSeries(media.value!.type) && !media.value!.kind) {
      items.push({ name: 'Сериалы', path: AppRoutes.serialy() })
    }

    if (isSeries(media.value!.type) && isAnimation(media.value!.kind)) {
      items.push({ name: 'Мультсериалы', path: AppRoutes.multserialy() })
    }

    items.push({
      name: media.value!.title,
      path: AppRoutes.watchModel(media.value!)
    })

    if (season.value && routeSeasonNumber.value) {
      items.push({
        name: `${season.value.number} сезон`,
        path: AppRoutes.watchSeasonModel(media.value!, season.value)
      })
    }

    if (season.value && episode.value && routeEpisodeNumber.value) {
      items.push({
        name: `${episode.value.number} серия`,
        path: AppRoutes.watchEpisodeModel(media.value!, season.value, episode.value)
      })
    }

    return items
  })

  const mainEntity = computed<string>(() => {
    if (isMovie(media.value!.type)) {
      return `${appUrl}${AppRoutes.watchModel(media.value!)}#movie`
    }

    if (isSeries(media.value!.type) && !routeSeasonNumber.value && !routeEpisodeNumber.value) {
      return `${appUrl}${AppRoutes.watchModel(media.value!)}#tvseries`
    }

    if (isSeries(media.value!.type) && routeEpisodeNumber.value) {
      return `${appUrl}${AppRoutes.watchEpisodeModel(media.value!, season.value!, episode.value!)}#episode`
    }

    if (isSeries(media.value!.type) && routeSeasonNumber.value) {
      return `${appUrl}${AppRoutes.watchSeasonModel(media.value!, season.value!)}#tvseason`
    }

    return `${appUrl}${route.path}`
  })

  const movieSchemas = computed<object[]>(() => {
    if (isMovie(media.value!.type)) {
      return [
        buildDetailMovieSchema({
          appUrl,
          media: media.value!
        })
      ]
    }

    return []
  })
  const seriesSchemas = computed<object[]>(() => {
    if (isSeries(media.value!.type) && !routeSeasonNumber.value && !routeEpisodeNumber.value) {
      return [
        buildDetailTvSeriesSchema({
          appUrl,
          media: media.value!
        })
      ]
    }

    return []
  })

  const seasonSchemas = computed<object[]>(() => {
    if (isSeries(media.value!.type) && routeSeasonNumber.value && !routeEpisodeNumber.value) {
      return [
        buildBaseTvSeriesSchema({
          appUrl,
          media: media.value!
        }),
        buildDetailTvSeasonSchema({
          appUrl,
          media: media.value!,
          season: season.value!
        })
      ]
    }

    return []
  })
  const episodeSchemas = computed<object[]>(() => {
    if (isSeries(media.value!.type) && routeEpisodeNumber.value) {
      return [
        buildBaseTvSeriesSchema({
          appUrl,
          media: media.value!
        }),
        buildBaseTvSeasonSchema({
          appUrl,
          media: media.value!,
          season: season.value!
        }),
        buildDetailTvEpisodeSchema({
          appUrl,
          media: media.value!,
          season: season.value!,
          episode: episode.value!
        })
      ]
    }

    return []
  })

  const schemas = computed<object[]>(() => [
    buildWebPageSchema({
      type: 'ItemPage',
      appUrl,
      canonicalUrl: canonicalUrl.value,
      title: title.value,
      description: description.value,
      mainEntity: mainEntity.value
    }),
    ...movieSchemas.value,
    ...seriesSchemas.value,
    ...seasonSchemas.value,
    ...episodeSchemas.value
  ])

  useJsonLd({
    canonicalUrl,
    breadcrumb,
    schemas
  })

  useHead(() => ({
    link: [{ rel: 'canonical', href: canonicalUrl.value }]
  }))

  useSeoMeta({
    title: () => title.value,
    description: () => description.value,
    ogType: () => ogType.value,
    ogTitle: () => title.value,
    ogDescription: () => description.value,
    ogUrl: () => canonicalUrl.value,
    ogImage: () => media.value!.poster || '',
    twitterCard: 'summary_large_image',
    twitterTitle: () => title.value,
    twitterDescription: () => description.value,
    twitterImage: () => media.value!.poster || ''
  })
}
