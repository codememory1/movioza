import type { MediaShortDto } from '#shared/types/media'
import type { PaginationMetaDto } from '#shared/types/common'

export interface SearchMediaListDto {
  data: MediaShortDto[]
  meta: SearchMediaListMetaDto
}

export interface SearchMediaListMetaDto {
  pagination: PaginationMetaDto
}
