import { collectionService } from '#shared/services/collection.service'

export const useHomePageCollections = () => {
  return useAsyncData('main-collections', () => collectionService.getHomePageList())
}
