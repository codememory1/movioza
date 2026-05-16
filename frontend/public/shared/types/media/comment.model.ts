import type { CommentModel } from '#shared/types/comment'
import type { PaginationMetaModel } from '#shared/types/common'

export interface MediaCommentsListModel {
  data: CommentModel[]
  meta: MediaCommentsListMetaModel
}

export interface MediaCommentsListMetaModel {
  pagination: PaginationMetaModel
}
