import type { MediaDetailModel, MediaShortModel } from '#shared/types/media'
import { toSchemaDateTime } from '#shared/utils/time.utils'
import { buildPersonSchema } from '#shared/seo/builders/person.schema'
import { AppRoutes } from '#shared/router/routes'
import { buildAggregateRatingSchema } from '#shared/seo/builders/aggregate-rating.schema'

export interface BuildItemMovieSchemaInput {
  appUrl: string
  media: MediaShortModel
}

export interface BuildDetailMovieSchemaInput {
  appUrl: string
  media: MediaDetailModel
}

export const buildItemMovieSchema = (input: BuildItemMovieSchemaInput) => ({
  '@type': 'Movie',
  '@id': `${input.appUrl}${input.media.watchPath}#movie`,
  name: input.media.title,
  description: input.media.description,
  image: input.media.poster.url,
  datePublished: toSchemaDateTime(input.media.releasedAt),
  director: input.media.directors.map((director) =>
    buildPersonSchema({
      appUrl: input.appUrl,
      person: director,
      profilePath: AppRoutes.director(director.id, director.slug)
    })
  ),
  url: `${input.appUrl}${AppRoutes.watchModel(input.media)}`
})

export const buildDetailMovieSchema = (input: BuildDetailMovieSchemaInput) => ({
  ...buildItemMovieSchema({
    appUrl: input.appUrl,
    media: input.media
  }),
  ...(input.media.alternativeTitles.length > 0 && {
    alternativeHeadline: input.media.alternativeTitles[0]
  }),
  genre: input.media.genres.map((genre) => genre.name),
  duration: secondsToIsoString(input.media.duration),
  contentRating: `${input.media.ageRestrictions}+`,
  dateModified: toSchemaDateTime(input.media.updatedAt || input.media.uploadedAt),
  aggregateRating: buildAggregateRatingSchema({
    rating: input.media.rating,
    maxRating: 10,
    votes: input.media.votes
  }),
  actor: input.media.actors.map((actor) =>
    buildPersonSchema({
      appUrl: input.appUrl,
      person: actor,
      profilePath: AppRoutes.actor(actor.id, actor.slug)
    })
  ),
  thumbnailUrl: input.media.poster.url
})
