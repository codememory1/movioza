<template>
  <div class="app-layout">
    <AppHeader
      v-model:search="search"
      v-model:search-drop-down-visible="searchDropDownVisible"
      :has-search-started
      :navigation-items="navigation"
      :search-loading="searchIsLoading"
      :search-items="searchMediaList?.data || []"
      @search="onSearch"
    />

    <main class="a-site-main">
      <section v-if="title || description" class="a-page-section">
        <AContainer>
          <header class="a-site-main__header">
            <h1 v-if="title" class="a-site-main__title">{{ title }}</h1>

            <p v-if="description" class="a-site-main__description">
              {{ description }}
            </p>

            <div v-if="page.stats.length > 0" class="a-site-main__stats">
              <p v-for="stat in page.stats" :key="stat.label" class="a-site-main__stat">
                {{ stat.label }}: <strong>{{ stat.value }}</strong>
              </p>
            </div>
          </header>
        </AContainer>
      </section>

      <slot />
    </main>

    <AppFooter :genres="popularGenres || []" />
  </div>
</template>

<script lang="ts" setup>
import AppHeader, { type AppHeaderNavigationItem } from '~/components/app/header/AppHeader.vue'
import AppFooter from '~/components/app/footer/AppFooter.vue'
import { useFeaturedGenres } from '~/composables/useFeaturedGenres'
import { usePage } from '~/composables/usePage'
import AContainer from '~/components/ui/container/AContainer.vue'
import { AppRoutes } from '#shared/router/routes'
import { useDebounceFn } from '@vueuse/core'
import { useSearchMediaList } from '~/composables/useSearchMediaList'

const SEARCH_MEDIA_LIMIT = 10

const route = useRoute()
const { state: page } = usePage()
const { data: popularGenres } = await useFeaturedGenres(10)

const search = ref<string | null>(null)
const title = computed(() => route.meta.pageTitle || route.meta.title)
const description = computed(() => route.meta.pageDescription || route.meta.description)
const navigation = computed<AppHeaderNavigationItem[]>(() => [
  {
    label: 'Фильмы',
    to: AppRoutes.filmy()
  },
  {
    label: 'Сериалы',
    to: AppRoutes.serialy()
  },
  {
    label: 'Коллекции',
    to: AppRoutes.collections()
  },
  {
    label: 'Подборки',
    to: ''
  }
])
const searchDropDownVisible = ref<boolean>(false)
const hasSearchStarted = ref<boolean>(false)

const {
  data: searchMediaList,
  execute: executeSearch,
  pending: searchIsLoading
} = await useSearchMediaList(search, 1, SEARCH_MEDIA_LIMIT, undefined, false)

const onSearch = useDebounceFn(async () => {
  searchDropDownVisible.value = true

  await executeSearch()

  hasSearchStarted.value = true
}, 500)
</script>

<style lang="scss">
@use '~/assets/scss/layouts/main';
</style>
