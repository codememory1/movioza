import { useCachedAsyncData } from '~/composables/useCachedAsyncData'
import { collectionService } from '#shared/services/collection.service'

export const useCollectionDetail = (id: MaybeRefOrGetter<number>) => {
  const resolvedId = computed(() => toValue(id))

  return useCachedAsyncData(`collection-${resolvedId.value}`, () =>
    collectionService.getDetailById(resolvedId.value)
  )
}
