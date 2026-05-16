<template>
  <div class="media-catalog">
    <MediaFilters
      :reset-loading="resetFiltersLoading"
      :accept-loading="acceptFiltersLoading"
      class="media-catalog__filters"
      @reset="emit('resetFilters')"
      @accept="emit('acceptFilters', $event)"
    />

    <div class="media-catalog__result">
      <div class="media-catalog__meta">
        <span>{{ resultsText }}</span>
      </div>
      <div class="media-catalog__grid">
        <MediaCard v-for="item in items" :key="item.id" :media="item" />
      </div>

      <APagination
        :model-value="currentPage"
        :total="totalPages"
        :link="pageLink"
        class="media-catalog__pagination"
      />
    </div>
  </div>
</template>

<script lang="ts" setup>
import MediaFilters from '~/components/features/media/MediaFilters.vue'
import type { MediaListFiltersModel, MediaShortModel } from '#shared/types/media'
import MediaCard from '~/components/features/media/MediaCard.vue'
import { plural } from '~/utils/plural.utils'
import APagination from '~/components/ui/pagination/APagination.vue'
import type { RouteLocationRaw } from 'vue-router'

export interface MediaCatalogProps {
  items: MediaShortModel[]
  totalPages: number
  currentPage: number
  pageLink: (page: number) => RouteLocationRaw
  resetFiltersLoading?: boolean
  acceptFiltersLoading?: boolean
}

export interface MediaCatalogEmits {
  resetFilters: []
  acceptFilters: [filters: MediaListFiltersModel]
}

const props = defineProps<MediaCatalogProps>()
const emit = defineEmits<MediaCatalogEmits>()
const resultsText = computed<string>(() => {
  const resultPlural = plural(props.items.length, ['результат', 'результата', 'результатов'])

  return `Показано ${props.items.length} ${resultPlural}`
})
</script>

<style lang="scss">
@use './styles/MediaCatalog.styles' as *;
</style>
