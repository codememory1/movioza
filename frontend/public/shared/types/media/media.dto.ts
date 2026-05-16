import type { GenreShortDto } from '#shared/types/genre'
import type { PersonDto } from '#shared/types/person'
import type { ImageDto, PaginationMetaDto } from '#shared/types/common'

export interface MediaShortDto {
  id: number
  slug: string
  type: string
  kind?: string
  title: string
  description: string
  poster: ImageDto
  rating: number
  duration: number
  quality: string
  genres: GenreShortDto[]
  directors: PersonDto[]
  released_at: number
}

export interface MediaDetailDto extends MediaShortDto {
  logo: string
  backdrop: ImageDto
  alternative_titles: string[]
  country: string
  age_restrictions: number
  votes: number
  episode_count?: number
  season_count?: number
  actors: PersonDto[]
  playback_id: number | null
  uploaded_at: number
  updated_at: number | null
}

export interface MediaListFiltersDto {
  genres: number[]
  countries: number[]
}

export interface MediaListDto {
  data: MediaShortDto[]
  meta: MediaListMetaDto
}

export interface MediaListMetaDto {
  pagination: PaginationMetaDto
}
