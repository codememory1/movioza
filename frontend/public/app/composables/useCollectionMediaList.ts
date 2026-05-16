import { collectionService } from '#shared/services/collection.service'

export const useCollectionMediaList = (
  id: MaybeRefOrGetter<number>,
  page: MaybeRefOrGetter<number>,
  limit: MaybeRefOrGetter<number>,
  immediate: boolean = true
) => {
  const resolvedId = computed<number>(() => toValue(id))
  const resolvedPage = computed<number>(() => toValue(page))
  const resolvedLimit = computed<number>(() => toValue(limit))

  return useAsyncData(
    `collection-${resolvedId.value}-media-list`,
    () => collectionService.getMediaList(resolvedId.value, resolvedPage.value, resolvedLimit.value),
    { immediate }
  )
}
