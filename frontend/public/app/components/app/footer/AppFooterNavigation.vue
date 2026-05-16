<template>
  <nav class="app-footer-nav" :aria-labelledby="id">
    <h2 :id="id" class="app-footer-nav__header">{{ header }}</h2>

    <ul class="app-footer-nav__list">
      <li v-for="item in items" :key="item.text" class="app-footer-nav__item">
        <NuxtLink :to="item.to">{{ item.text }}</NuxtLink>
      </li>
    </ul>
  </nav>
</template>

<script lang="ts" setup>
import { NuxtLink } from '#components'
import type { RouteLocationRaw } from 'vue-router'

export interface AppFooterNavigationItem {
  text: string
  to: RouteLocationRaw
}

export interface AppFooterNavigationProps {
  header: string
  items: AppFooterNavigationItem[]
}

defineProps<AppFooterNavigationProps>()

const id = useId()
</script>

<style lang="scss">
@use './styles/AppFooterNavigation.styles' as *;
</style>
