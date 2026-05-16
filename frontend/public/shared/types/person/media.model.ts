import type { MediaShortModel } from '#shared/types/media'
import type { PaginationMetaModel } from '#shared/types/common'

export interface PersonMediaListMetaModel {
  pagination: PaginationMetaModel
}

export interface PersonMediaListModel {
  data: MediaShortModel[]
  meta: PersonMediaListMetaModel
}
