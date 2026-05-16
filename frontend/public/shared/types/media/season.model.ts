import type { EpisodeModel } from '#shared/types/media'
import type { ImageModel } from '#shared/types/common'

export interface SeasonShortModel {
  number: number
  title: string
  description: string | null
  episodeCount: number
  watchSegment: string // Created on the client
  releasedAt: Date
}

export interface SeasonModel extends SeasonShortModel {
  backdrop: ImageModel | null
  episodes: EpisodeModel[]
  uploadedAt: Date
  updatedAt: Date | null
}
