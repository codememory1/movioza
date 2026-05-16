import type { NuxtPage } from 'nuxt/schema'

export default [
  {
    name: 'watch-season',
    path: '/watch/:slug/sezon-:season(\\d+)',
    file: '~/pages/watch/[slug]/index.vue'
  },
  {
    name: 'watch-episode',
    path: '/watch/:slug/sezon-:season(\\d+)/seriya-:episode(\\d+)',
    file: '~/pages/watch/[slug]/index.vue'
  }
] satisfies NuxtPage[]
