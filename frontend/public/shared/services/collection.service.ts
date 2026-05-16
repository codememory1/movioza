import { useNuxtApp } from '#imports'
import { collectionMapper } from '#shared/mappers/collection.mapper'
import type {
  CollectionDetailModel,
  CollectionShortModel,
  CollectionsListModel
} from '#shared/types/collection'

export const collectionService = {
  async getHomePageList(): Promise<CollectionShortModel[]> {
    const { $api } = useNuxtApp()
    const response = await $api('/main-collections')

    return response.data.map(collectionMapper.toShortModel)
  },

  async getMediaList(collectionId: number, page: number, limit: number = 25) {
    const { $api } = useNuxtApp()
    const response = await $api(`/collections/${collectionId}/media?page=${page}&limit=${limit}`)

    return collectionMapper.toMediaListModel(response)
  },

  async getList(page: number, limit: number = 25): Promise<CollectionsListModel> {
    const { $api } = useNuxtApp()
    const response = await $api(`/collections?page=${page}&limit=${limit}`)

    return collectionMapper.toListModel(response)
  },

  async getDetailById(id: number): Promise<CollectionDetailModel> {
    const { $api } = useNuxtApp()
    const response = await $api(`/collections/${id}`)

    return collectionMapper.toDetailModel(response.data)
  }
}
