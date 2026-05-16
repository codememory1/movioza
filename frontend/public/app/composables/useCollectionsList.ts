import { collectionService } from '#shared/services/collection.service'

export const useCollectionsList = (
  page: MaybeRefOrGetter<number>,
  limit: MaybeRefOrGetter<number>
) => {
  const resolvedPage = computed(() => toValue(page))
  const resolvedLimit = computed(() => toValue(limit))
  const key = computed<string>(
    () =>
      `collections:${JSON.stringify({
        page: resolvedPage.value,
        limit: resolvedLimit.value
      })}`
  )

  return useAsyncData(key.value, () =>
    collectionService.getList(resolvedPage.value, resolvedLimit.value)
  )
}
