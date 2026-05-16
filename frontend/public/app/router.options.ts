import type { RouterConfig } from '@nuxt/schema'
import qs from 'qs'
import type { LocationQuery } from 'vue-router'

export default {
  parseQuery: (query) =>
    qs.parse(query, {
      ignoreQueryPrefix: true,
      comma: true
    }) as LocationQuery,

  stringifyQuery: (query) =>
    qs.stringify(query, {
      skipNulls: true,
      arrayFormat: 'comma',
      encodeValuesOnly: true
    })
} satisfies RouterConfig
