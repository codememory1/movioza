import type { MediaShortDto } from '#shared/types/media'
import type { PaginationMetaDto } from '#shared/types/common'

export interface CollectionShortDto {
  id: number
  slug: string
  title: string
}

export interface CollectionDetailDto extends CollectionShortDto {
  description: string
  media_count: number
}

// List
export interface CollectionsListDto {
  data: CollectionDetailDto[]
  meta: CollectionsListMetaDto
}

export interface CollectionsListMetaDto {
  pagination: PaginationMetaDto
}

// Collection Media
export interface CollectionMediaListDto {
  data: MediaShortDto[]
  meta: CollectionMediaListMetaDto
}

export interface CollectionMediaListMetaDto {
  pagination: PaginationMetaDto
}
