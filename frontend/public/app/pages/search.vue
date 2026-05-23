<template>
  <div class="search-page">
    <AContainer class="a-section-mt--sm">
      <AInput
        v-model="search"
        placeholder="Поиск..."
        :prepend-icon="ASearchIcon"
        class="search-page__input"
        @update:model-value="onSearchInput"
      />
    </AContainer>
    <AContainer class="a-section-mt--sm">
      <MediaCatalog
        v-if="mediaList"
        :items="mediaList.data"
        :total-pages="mediaList.meta.pagination.totalPages"
        :current-page="page"
        :loading="pending"
        :accept-filters-loading="acceptFiltersIsLoading"
        :reset-filters-loading="resetFiltersIsLoading"
        :page-link="
          (number: number) => ({
            path: AppRoutes.searchPage(number),
            query: route.query
          })
        "
        @accept-filters="acceptFilters"
        @reset-filters="resetFilters"
      />
    </AContainer>
  </div>
</template>

<script lang="ts" setup>
import AInput from '~/components/ui/input/AInput.vue'
import ASearchIcon from '~/components/ui/icon/ASearchIcon.vue'
import AContainer from '~/components/ui/container/AContainer.vue'
import { useAsyncAction, useMediaFilters, useRoute, useRouter } from '#imports'
import MediaCatalog from '~/components/features/media/MediaCatalog.vue'
import { useSearchMediaList } from '~/composables/useSearchMediaList'
import { AppRoutes } from '#shared/router/routes'
import { useDebounceFn } from '@vueuse/core'

definePageMeta({
  pageTitle: 'Поиск фильмов и сериалов',
  pageDescription: 'Поиск фильмов и сериалов по названию, жанрам и другим параметрам.'
})

const MEDIA_LIMIT = 20

const router = useRouter()
const route = useRoute()
const { filters } = useMediaFilters()

const search = ref<string | null>(typeof route.query.q === 'string' ? route.query.q : null)
const page = ref<number>(Number(route.params.page || 1) || 1)

const {
  data: mediaList,
  pending,
  execute
} = await useSearchMediaList(search, page, MEDIA_LIMIT, filters)

const onSearchInput = useDebounceFn(async () => {
  await router.replace({
    query: {
      ...route.query,
      q: search.value
    }
  })

  await execute()
}, 500)

const { pending: acceptFiltersIsLoading, run: acceptFilters } = useAsyncAction(
  async () => await execute()
)
const { pending: resetFiltersIsLoading, run: resetFilters } = useAsyncAction(
  async () => await execute()
)
</script>

<style lang="scss">
@use '~/assets/scss/pages/search' as *;
</style>
