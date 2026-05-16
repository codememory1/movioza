import type { MediaListFiltersModel } from '#shared/types/media'
import { mediaService } from '#shared/services/media.service'

export const useSeriesList = (
  page: MaybeRefOrGetter<number>,
  limit: MaybeRefOrGetter<number>,
  filters?: MaybeRefOrGetter<MediaListFiltersModel>
) => {
  const resolvedPage = computed<number>(() => toValue(page))
  const resolvedLimit = computed<number>(() => toValue(limit))
  const resolvedFilters = computed<MediaListFiltersModel | undefined>(() => toValue(filters))
  const key = computed<string>(
    () =>
      `series-list:${JSON.stringify({
        page: resolvedPage.value,
        limit: resolvedLimit.value,
        filters: resolvedFilters.value
      })}`
  )

  return useAsyncData(key.value, () =>
    mediaService.getSeriesList(resolvedPage.value, resolvedLimit.value, resolvedFilters.value)
  )
}
