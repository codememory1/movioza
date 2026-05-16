import type { MediaShortDto } from '#shared/types/media'
import type { PaginationMetaDto } from '#shared/types/common'

export interface PersonMediaListMetaDto {
  pagination: PaginationMetaDto
}

export interface PersonMediaListDto {
  data: MediaShortDto[]
  meta: PersonMediaListMetaDto
}
