import type { EpisodeModel, MediaDetailModel, SeasonModel } from '#shared/types/media'
import { toSchemaDateTime } from '#shared/utils/time.utils'

export interface BuildDetailTvEpisodeSchemaInput {
  appUrl: string
  media: MediaDetailModel
  season: SeasonModel
  episode: EpisodeModel
}

export const buildDetailTvEpisodeSchema = (input: BuildDetailTvEpisodeSchemaInput) => {
  const watchUrl = `${input.appUrl}${input.media.watchPath}/${input.season.watchSegment}/${input.episode.watchSegment}`

  return {
    '@type': 'TVEpisode',
    '@id': `${watchUrl}#episode`,
    name: `${input.media.title} — ${input.season.number} сезон ${input.episode.number} серия`,
    genre: input.media.genres.map((genre) => genre.name),
    episodeNumber: input.episode.number,
    url: watchUrl,
    image: input.media.poster.url,
    description: input.episode.description?.trim() || input.media.description,
    datePublished: toSchemaDateTime(input.episode.releasedAt),
    dateModified: toSchemaDateTime(input.episode.updatedAt || input.episode.uploadedAt),
    partOfSeason: {
      '@id': `${input.appUrl}${input.media.watchPath}/${input.season.watchSegment}#tvseason`
    },
    partOfSeries: {
      '@id': `${input.appUrl}${input.media.watchPath}#tvseries`
    }
  }
}
