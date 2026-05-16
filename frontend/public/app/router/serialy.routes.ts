import type { NuxtPage } from 'nuxt/schema'

export default [
  {
    name: 'serial-page',
    path: '/serialy/page/:page(\\d+)',
    file: '~/pages/serialy/index.vue'
  }
] satisfies NuxtPage[]
