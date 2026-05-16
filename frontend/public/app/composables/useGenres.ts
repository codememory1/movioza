import { genreService } from '#shared/services/genre.service'

export const useGenres = (limit?: MaybeRefOrGetter<number>) => {
  const resolvedLimit = computed<number | undefined>(() => toValue(limit))

  return useAsyncData('genres', () => genreService.all(resolvedLimit.value))
}
