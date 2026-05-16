import type { EpisodeDto } from '#shared/types/media'
import type { ImageDto } from '#shared/types/common'

export interface SeasonShortDto {
  number: number
  title: string
  description: string | null
  episode_count: number
  released_at: number
}

export interface SeasonDto extends SeasonShortDto {
  backdrop: ImageDto | null
  episodes: EpisodeDto[]
  uploaded_at: number
  updated_at: number | null
}
