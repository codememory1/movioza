import type { MediaListFiltersModel } from '#shared/types/media'
import { searchService } from '#shared/services/search.service'

export const useSearchMediaList = (
  query: MaybeRefOrGetter<string | null>,
  page: MaybeRefOrGetter<number>,
  limit: MaybeRefOrGetter<number>,
  filters?: MaybeRefOrGetter<MediaListFiltersModel>,
  immediate: boolean = true
) => {
  const resolvedQuery = computed(() => toValue(query))
  const resolvedPage = computed(() => toValue(page))
  const resolvedLimit = computed(() => toValue(limit))
  const resolvedFilters = computed(() => toValue(filters))

  const key = `search-media-list:${JSON.stringify({
    query: resolvedQuery.value,
    page: resolvedPage.value,
    limit: resolvedLimit.value,
    filters: resolvedFilters.value
  })}`

  return useAsyncData(
    key,
    () =>
      searchService.searchMedia(
        resolvedQuery.value,
        resolvedPage.value,
        resolvedLimit.value,
        resolvedFilters.value
      ),
    { immediate }
  )
}
