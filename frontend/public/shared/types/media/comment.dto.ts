import type { CommentDto } from '#shared/types/comment'
import type { PaginationMetaDto } from '#shared/types/common'

export interface MediaCommentsListDto {
  data: CommentDto[]
  meta: MediaCommentsListMetaDto
}

export interface MediaCommentsListMetaDto {
  pagination: PaginationMetaDto
}
