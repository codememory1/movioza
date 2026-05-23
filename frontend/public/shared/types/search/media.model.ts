import type { PaginationMetaModel } from '#shared/types/common'
import type { MediaShortModel } from '#shared/types/media'

export interface SearchMediaListModel {
  data: MediaShortModel[]
  meta: SearchMediaListMetaModel
}

export interface SearchMediaListMetaModel {
  pagination: PaginationMetaModel
}
