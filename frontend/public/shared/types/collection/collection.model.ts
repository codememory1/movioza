import type { MediaShortModel } from '#shared/types/media'
import type { PaginationMetaModel } from '#shared/types/common'

export interface CollectionShortModel {
  id: number
  slug: string
  title: string
}

export interface CollectionDetailModel extends CollectionShortModel {
  description: string
  mediaCount: number
}

// List
export interface CollectionsListModel {
  data: CollectionDetailModel[]
  meta: CollectionsListMetaModel
}

export interface CollectionsListMetaModel {
  pagination: PaginationMetaModel
}

// Collection Media
export interface CollectionMediaListModel {
  data: MediaShortModel[]
  meta: CollectionMediaListMetaModel
}

export interface CollectionMediaListMetaModel {
  pagination: PaginationMetaModel
}
