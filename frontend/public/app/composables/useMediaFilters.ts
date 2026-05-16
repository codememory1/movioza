import { toArray } from '#shared/utils/normalize.utils'
import type { MediaListFiltersModel } from '#shared/types/media'
import type { LocationQuery } from 'vue-router'

export const useMediaFilters = () => {
  const router = useRouter()
  const route = useRoute()

  const filters = ref<MediaListFiltersModel>({
    genres: [],
    countries: []
  })
  const query = computed<LocationQuery>(() => route.query)
  const filtersFromQuery = computed<MediaListFiltersModel>(() => ({
    genres: toArray(query.value.genres).map(String),
    countries: toArray(query.value.countries).map(String)
  }))
  const has = computed<boolean>(() => {
    return Object.values(filtersFromQuery.value).some((value) => value.length > 0)
  })

  watch(
    filtersFromQuery,
    () => {
      filters.value = filtersFromQuery.value
    },
    { immediate: true }
  )

  const accept = async () => {
    await router.replace({
      query: { ...query.value, ...filters.value }
    })
  }

  const reset = async () => {
    filters.value.genres = []
    filters.value.countries = []

    await accept()
  }

  return {
    filters,
    has,
    accept,
    reset
  }
}
