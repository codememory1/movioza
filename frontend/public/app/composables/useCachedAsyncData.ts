import type { AsyncDataOptions } from 'nuxt/app'

export const useCachedAsyncData = <T>(
  key: string,
  handler: () => Promise<T>,
  options?: AsyncDataOptions<T>
) => {
  const cached = useNuxtData<T>(key)

  if (cached.data.value !== undefined) {
    return cached
  }

  return useAsyncData<T>(key, handler, options)
}
