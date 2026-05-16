<template>
  <header class="app-header">
    <div class="app-header__container">
      <NuxtLink to="/" class="app-header__logo" :aria-label="`${title} — на главную`">
        <NuxtImg :src="logo" :width="36" :height="36" :alt="title" />
        <span class="app-header__title">{{ title }}</span>
      </NuxtLink>

      <nav class="app-header__nav" aria-label="Навигация">
        <ul class="app-header__menu">
          <li v-for="item in items" :key="item.label">
            <NuxtLink :to="item.to">{{ item.label }}</NuxtLink>
          </li>
        </ul>
      </nav>

      <div class="app-header__search">
        <AInput v-model="search" placeholder="Поиск..." :prepend-icon="ASearchIcon" />
      </div>
    </div>
  </header>
</template>

<script lang="ts" setup>
import type { RouteLocationRaw } from 'vue-router'
import AInput from '~/components/ui/input/AInput.vue'
import ASearchIcon from '~/components/ui/icon/ASearchIcon.vue'

export interface ASiteHeaderMenuItem {
  label: string
  to: RouteLocationRaw
}

export interface ASiteHeaderProps {
  items: ASiteHeaderMenuItem[]
}

defineProps<ASiteHeaderProps>()

const config = useRuntimeConfig()
const logo = computed<string>(() => config.public.appHeaderLogo)
const title = computed<string>(() => config.public.appShortTitle)
const search = ref<string | null>(null)
</script>

<style lang="scss">
@use './styles/AppHeader.styles' as *;
</style>
