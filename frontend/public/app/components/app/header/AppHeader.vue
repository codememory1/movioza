<template>
  <header class="app-header">
    <div class="app-header__container">
      <NuxtLink to="/" class="app-header__logo" :aria-label="`${title} — на главную`">
        <NuxtImg :src="logo" :width="36" :height="36" :alt="title" />
        <span class="app-header__title">{{ title }}</span>
      </NuxtLink>

      <nav class="app-header__nav" aria-label="Навигация">
        <ul class="app-header__menu">
          <li v-for="item in navigationItems" :key="item.label">
            <NuxtLink :to="item.to">{{ item.label }}</NuxtLink>
          </li>
        </ul>
      </nav>

      <div ref="searchRef" class="app-header__search">
        <AInput
          ref="searchInputRef"
          v-model="search"
          placeholder="Поиск..."
          :prepend-icon="searchLoading ? ALoaderCircleIcon : ASearchIcon"
          @update:model-value="emit('search', $event)"
          @focus="searchDropDownVisible = true"
        />

        <SearchDropDown
          v-show="search && searchDropDownVisible && (hasSearchStarted || searchLoading)"
          :loading="searchLoading"
          :query="search"
          :items="searchItems"
        />
      </div>
    </div>
  </header>
</template>

<script lang="ts" setup>
import type { RouteLocationRaw } from 'vue-router'
import AInput from '~/components/ui/input/AInput.vue'
import ASearchIcon from '~/components/ui/icon/ASearchIcon.vue'
import SearchDropDown from '~/components/features/search/SearchDropDown.vue'
import type { MediaShortModel } from '#shared/types/media'
import ALoaderCircleIcon from '~/components/ui/icon/ALoaderCircleIcon.vue'
import { onClickOutside } from '@vueuse/core'

export interface AppHeaderNavigationItem {
  label: string
  to: RouteLocationRaw
}

export interface AppHeaderProps {
  navigationItems: AppHeaderNavigationItem[]
  searchItems: MediaShortModel[]
  searchLoading?: boolean
  hasSearchStarted?: boolean
}

export interface AppHeaderEmits {
  search: [query: string | null]
}

const config = useRuntimeConfig()

defineProps<AppHeaderProps>()

const emit = defineEmits<AppHeaderEmits>()
const searchRef = ref<HTMLElement>()
const search = defineModel<string | null>('search', { required: true })
const searchDropDownVisible = defineModel<boolean>('searchDropDownVisible', { required: true })

const logo = computed<string>(() => config.public.appHeaderLogo)
const title = computed<string>(() => config.public.appShortTitle)

onClickOutside(searchRef, () => {
  searchDropDownVisible.value = false
})
</script>

<style lang="scss">
@use './styles/AppHeader.styles' as *;
</style>
