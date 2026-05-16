import type { NuxtPage } from 'nuxt/schema'

export default [
  {
    name: 'filmy-page',
    path: '/filmy/page/:page(\\d+)',
    file: '~/pages/filmy/index.vue'
  }
] satisfies NuxtPage[]
