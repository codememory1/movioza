<template>
  <AContainer class="a-section-mt--sm">
    <MediaCatalog
      v-if="mediaList"
      :filters
      :reset-filters-loading="resetFiltersIsLoading"
      :accept-filters-loading="acceptFiltersIsLoading"
      :items="mediaList.data"
      :total-pages="mediaList.meta.pagination.totalPages"
      :current-page="page"
      :page-link="
        (num: number) => ({
          path: AppRoutes.filmyPage(num),
          query: route.query
        })
      "
      @reset-filters="resetFilters"
      @accept-filters="acceptFilters"
    />
  </AContainer>
</template>

<script lang="ts" setup>
import AContainer from '~/components/ui/container/AContainer.vue'
import MediaCatalog from '~/components/features/media/MediaCatalog.vue'
import { useMediaFilters } from '~/composables/useMediaFilters'
import { useAsyncAction } from '~/composables/useAsyncAction'
import { useMoviesList } from '~/composables/useMoviesList'
import { useRoute } from '#imports'
import { AppRoutes } from '#shared/router/routes'
import { useFilmySeo } from '~/composables/useFilmySeo'

const route = useRoute()
const LIMIT = 50

definePageMeta({
  pageTitle: 'Фильмы',
  pageDescription: 'Все фильмы в одном месте — от новинок до популярных.',
  layout: 'default'
})

const page = computed<number>(() => Number(route.params.page || 1))

const { filters, has: hasFilters } = useMediaFilters()
const { data: mediaList, execute } = await useMoviesList(page, LIMIT, filters)
const { pending: resetFiltersIsLoading, run: resetFilters } = useAsyncAction(
  async () => await execute()
)
const { pending: acceptFiltersIsLoading, run: acceptFilters } = useAsyncAction(
  async () => await execute()
)

useFilmySeo({
  page,
  hasFilters,
  limit: LIMIT,
  mediaList
})
</script>
