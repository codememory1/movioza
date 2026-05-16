import type { MediaSourceModel } from '#shared/types/media-source'
import type { ImageModel } from '#shared/types/common'

export interface EpisodeShortModel {
  number: number
  title: string
  description: string | null
  duration: number
  watchSegment: string // Created on the client
  releasedAt: Date
}

export interface EpisodeModel extends EpisodeShortModel {
  previewSources: MediaSourceModel[]
  preview: ImageModel | null
  playbackId: number | null
  uploadedAt: Date
  updatedAt: Date | null
}
