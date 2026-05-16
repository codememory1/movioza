import type { NuxtPage } from 'nuxt/schema'

export default [
  {
    name: 'collections-page',
    path: '/collections/page/:page(\\d+)',
    file: '~/pages/collections/index.vue'
  },
  {
    name: 'collection-page',
    path: '/collections/:slug/page/:page(\\d+)',
    file: '~/pages/collections/[slug].vue'
  }
] satisfies NuxtPage[]
