import type { MediaType } from '#shared/constants/media-type.constants'
import type { MediaKind } from '#shared/constants/media-kind.constants'
import type { GenreShortModel } from '#shared/types/genre'
import type { PersonModel } from '#shared/types/person'
import type { ImageModel, PaginationMetaModel } from '#shared/types/common'

export interface MediaShortModel {
  id: number
  slug: string
  type: MediaType
  kind?: MediaKind
  title: string
  description: string
  poster: ImageModel
  rating: number
  duration: number
  quality: string
  genres: GenreShortModel[]
  directors: PersonModel[]
  watchPath: string // Created on the client
  releasedAt: Date
}

export interface MediaDetailModel extends MediaShortModel {
  logo: string
  backdrop: ImageModel
  alternativeTitles: string[]
  country: string
  ageRestrictions: number
  votes: number
  episodeCount?: number
  seasonCount?: number
  actors: PersonModel[]
  playbackId: number | null
  uploadedAt: Date
  updatedAt: Date | null
}

export interface MediaListFiltersModel {
  genres: string[]
  countries: string[]
}

export interface MediaListModel {
  data: MediaShortModel[]
  meta: MediaListMetaModel
}

export interface MediaListMetaModel {
  pagination: PaginationMetaModel
}
