import type { MediaSourceDto } from '#shared/types/media-source'
import type { ImageDto } from '#shared/types/common'

export interface EpisodeShortDto {
  number: number
  title: string
  description: string | null
  duration: number
  released_at: number
}

export interface EpisodeDto extends EpisodeShortDto {
  preview_sources: MediaSourceDto[]
  preview: ImageDto | null
  playback_id: number | null
  uploaded_at: number
  updated_at: number | null
}
