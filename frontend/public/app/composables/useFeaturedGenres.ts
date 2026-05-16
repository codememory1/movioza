import { genreService } from '#shared/services/genre.service'

export const useFeaturedGenres = (limit?: MaybeRefOrGetter<number>) => {
  const resolvedLimit = computed<number | undefined>(() => toValue(limit))

  return useAsyncData('featured-genres', () => genreService.featured(resolvedLimit.value))
}
