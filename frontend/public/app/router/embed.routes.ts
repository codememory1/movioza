import type { NuxtPage } from 'nuxt/schema'

export default [
  {
    name: 'embed-season',
    path: '/embed/:slug/sezon-:season(\\d+)',
    file: '~/pages/embed/[slug]/index.vue',
    meta: {
      layout: false
    }
  },
  {
    name: 'embed-episode',
    path: '/embed/:slug/sezon-:season(\\d+)/seriya-:episode(\\d+)',
    file: '~/pages/embed/[slug]/index.vue',
    meta: {
      layout: false
    }
  }
] satisfies NuxtPage[]
