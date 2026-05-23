<template>
  <div class="search-drop-down">
    <div class="search-drop-down__list">
      <slot v-if="loading" name="skeletonList">
        <SearchDropDownItem v-for="i in skeletonItems" :key="i" />
      </slot>
      <slot v-else-if="items.length > 0" name="list" :items="items">
        <SearchDropDownItem v-for="item in items" :key="item.id" :media="item" />
      </slot>
      <slot v-else name="empty">
        <p class="search-drop-down__empty">Ничего не найдено</p>
      </slot>
    </div>

    <div class="search-drop-down__footer">
      <div class="search-drop-down__footer-inner">
        <NuxtLink
          :to="{ path: AppRoutes.search(), query: { q: query } }"
          class="search-drop-down__all-results-btn"
        >
          Показать все результаты <AArrowRightIcon />
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import AArrowRightIcon from '~/components/ui/icon/AArrowRightIcon.vue'
import { AppRoutes } from '#shared/router/routes'
import type { MediaShortModel } from '#shared/types/media'
import SearchDropDownItem from '~/components/features/search/SearchDropDownItem.vue'

export interface SearchDropDownProps {
  query: string | null
  items: MediaShortModel[]
  loading?: boolean
  skeletonItems?: number
}

withDefaults(defineProps<SearchDropDownProps>(), {
  skeletonItems: 7
})
</script>

<style lang="scss">
@use './styles/SearchDropDown.styles' as *;
</style>
