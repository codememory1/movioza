import type { NuxtPage } from 'nuxt/schema'
import filmyRoutes from './filmy.routes'
import serialyRoutes from './serialy.routes'
import watchRoutes from './watch.routes'
import embedRoutes from './embed.routes'
import collectionsRoutes from './collection.routes'
import searchRoutes from './search.routes'

export const routes: NuxtPage[] = [
  ...filmyRoutes,
  ...serialyRoutes,
  ...watchRoutes,
  ...embedRoutes,
  ...collectionsRoutes,
  ...searchRoutes
]
