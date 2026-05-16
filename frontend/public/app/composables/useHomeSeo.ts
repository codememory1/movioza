import { buildWebPageSchema } from '#shared/seo/builders/webpage.schema'
import type { MaybeRefOrGetter } from 'vue'
import type { MediaShortModel } from '#shared/types/media'
import { buildItemListSchema } from '#shared/seo/builders/item-list.schema'
import type { CollectionModel } from '#shared/types/collection/collection.model'
import { AppRoutes } from '#shared/router/routes'
import { buildItemMovieSchema } from '#shared/seo/builders/movie.schema'
import { buildItemTvSeriesSchema } from '#shared/seo/builders/tv-series.schema'

export interface HomeSeoCollection {
  entity: CollectionModel
  media: MediaShortModel[]
}

export interface UseHomeSeoOptions {
  featuredMedia: MaybeRefOrGetter<MaybeNullish<MediaShortModel>>
  collections: MaybeRefOrGetter<HomeSeoCollection[]>
}

export const useHomeSeo = (options: UseHomeSeoOptions) => {
  const config = useRuntimeConfig()
  const appUrl = config.public.appUrl

  const featuredMedia = computed(() => toValue(options.featuredMedia))
  const canonicalUrl = computed<string>(() => config.public.appUrl)
  const featuredMediaSchema = computed(() => {
    if (featuredMedia.value && isMovie(featuredMedia.value.type)) {
      return buildItemMovieSchema({ appUrl, media: featuredMedia.value })
    }

    if (featuredMedia.value && isSeries(featuredMedia.value.type)) {
      return buildItemTvSeriesSchema({ appUrl, media: featuredMedia.value })
    }

    return undefined
  })
  const schemas = computed<object[]>(() => {
    const result: object[] = []

    result.push(
      buildWebPageSchema({
        appUrl,
        breadcrumbHidden: true,
        canonicalUrl: canonicalUrl.value,
        title: config.public.appFullTitle,
        description: config.public.appDescription,
        mainEntity: featuredMediaSchema.value?.['@id']
      })
    )

    if (featuredMediaSchema.value) {
      result.push(featuredMediaSchema.value)
    }

    toValue(options.collections)?.forEach((collection) => {
      result.push(
        buildItemListSchema({
          canonicalUrl: canonicalUrl.value,
          id: `${appUrl}${AppRoutes.collectionModel(collection.entity)}#collection`,
          name: collection.entity.title,
          items: collection.media.map((media) => ({
            url: `${appUrl}${AppRoutes.watchModel(media)}`,
            item: buildMediaItemSchema(media)
          }))
        })
      )
    })

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
    link: [
      {
        rel: 'canonical',
        href: canonicalUrl.value
      }
    ]
  }))

  useJsonLd({ canonicalUrl, breadcrumb: false, schemas })
}
