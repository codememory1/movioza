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
          path: AppRoutes.serialyPage(num),
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
import { useRoute } from '#imports'
import { AppRoutes } from '#shared/router/routes'
import { useSeriesList } from '~/composables/useSeriesList'
import { useSerialySeo } from '~/composables/useSerialySeo'

const route = useRoute()
const LIMIT = 50

definePageMeta({
  pageTitle: 'Сериалы',
  pageDescription: 'Все сериалы в одном месте — от новинок до популярных.'
})

const page = computed<number>(() => Number(route.params.page || 1))

const { filters, has: hasFilters } = useMediaFilters()
const { data: mediaList, execute } = await useSeriesList(page, LIMIT, filters)
const { pending: resetFiltersIsLoading, run: resetFilters } = useAsyncAction(
  async () => await execute()
)
const { pending: acceptFiltersIsLoading, run: acceptFilters } = useAsyncAction(
  async () => await execute()
)

useSerialySeo({
  page,
  hasFilters,
  limit: LIMIT,
  mediaList
})
</script>
