import type { MediaDetailModel, MediaShortModel, SeasonModel } from '#shared/types/media'
import { toSchemaDateTime } from '#shared/utils/time.utils'

export interface BuildBaseTvSeasonSchemaInput {
  appUrl: string
  media: MediaShortModel
  season: SeasonModel
}

export interface BuildDetailTvSeasonSchemaInput {
  appUrl: string
  media: MediaDetailModel
  season: SeasonModel
}

export const buildBaseTvSeasonSchema = (input: BuildBaseTvSeasonSchemaInput) => ({
  '@type': 'TVSeason',
  '@id': `${input.appUrl}${input.media.watchPath}/${input.season.watchSegment}#tvseason`,
  name: `${input.media.title} — ${input.season.number} сезон`,
  seasonNumber: input.season.number,
  url: `${input.appUrl}${input.media.watchPath}/${input.season.watchSegment}`,
  partOfSeries: {
    '@id': `${input.appUrl}${input.media.watchPath}#tvseries`
  }
})

export const buildDetailTvSeasonSchema = (input: BuildDetailTvSeasonSchemaInput) => {
  return {
    ...buildBaseTvSeasonSchema({
      appUrl: input.appUrl,
      media: input.media,
      season: input.season
    }),
    image: input.media.poster.url,
    description: input.media.description,
    numberOfEpisodes: input.season.episodeCount,
    dateModified: toSchemaDateTime(input.season.updatedAt || input.season.uploadedAt)
  }
}
