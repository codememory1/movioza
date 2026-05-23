import type { NuxtPage } from 'nuxt/schema'

export default [
  {
    name: 'search-page',
    path: '/search/page/:page(\\d+)',
    file: '~/pages/search.vue'
  }
] satisfies NuxtPage[]
