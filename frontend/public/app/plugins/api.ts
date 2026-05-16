export default defineNuxtPlugin(() => {
  const config = useRuntimeConfig()
  const api = $fetch.create({
    baseURL: config.public.apiUrl,
    headers: {
      Accept: 'application/json'
    }
  })

  return {
    provide: {
      api
    }
  }
})
