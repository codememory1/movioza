import type { MediaDetailModel, MediaShortModel } from '#shared/types/media'
import { toSchemaDateTime } from '#shared/utils/time.utils'
import { buildAggregateRatingSchema } from '#shared/seo/builders/aggregate-rating.schema'
import { buildPersonSchema } from '#shared/seo/builders/person.schema'
import { AppRoutes } from '#shared/router/routes'

export interface BuildBaseTvSeriesSchemaInput {
  appUrl: string
  media: MediaShortModel
}

export interface BuildItemTvSeriesSchemaInput {
  appUrl: string
  media: MediaShortModel
}

export interface BuildDetailTvSeriesSchemaInput {
  appUrl: string
  media: MediaDetailModel
}

export const buildBaseTvSeriesSchema = (input: BuildBaseTvSeriesSchemaInput) => ({
  '@type': 'TVSeries',
  '@id': `${input.appUrl}${input.media.watchPath}#tvseries`,
  name: input.media.title,
  image: input.media.poster.url,
  url: `${input.appUrl}${AppRoutes.watchModel(input.media)}`
})

export const buildItemTvSeriesSchema = (input: BuildItemTvSeriesSchemaInput) => ({
  ...buildBaseTvSeriesSchema({
    appUrl: input.appUrl,
    media: input.media
  }),
  description: input.media.description,
  datePublished: toSchemaDateTime(input.media.releasedAt),
  genre: input.media.genres.map((genre) => genre.name)
})

export const buildDetailTvSeriesSchema = (input: BuildDetailTvSeriesSchemaInput) => ({
  ...buildBaseTvSeriesSchema({
    appUrl: input.appUrl,
    media: input.media
  }),
  description: input.media.description,
  datePublished: toSchemaDateTime(input.media.releasedAt),
  genre: input.media.genres.map((genre) => genre.name),
  contentRating: `${input.media.ageRestrictions}+`,
  numberOfSeasons: input.media.seasonCount,
  numberOfEpisodes: input.media.episodeCount,
  dateModified: toSchemaDateTime(input.media.updatedAt || input.media.uploadedAt),
  aggregateRating: buildAggregateRatingSchema({
    rating: input.media.rating,
    maxRating: 10,
    votes: input.media.votes
  }),
  thumbnailUrl: input.media.poster.url,
  director: input.media.directors.map((person) =>
    buildPersonSchema({
      appUrl: input.appUrl,
      person,
      profilePath: AppRoutes.director(person.id, person.slug)
    })
  ),
  actor: input.media.actors.map((person) =>
    buildPersonSchema({
      appUrl: input.appUrl,
      person,
      profilePath: AppRoutes.actor(person.id, person.slug)
    })
  )
})
