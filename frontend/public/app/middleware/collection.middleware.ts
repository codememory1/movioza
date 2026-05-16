import { useSlugParser } from '~/composables/useSlugParser'
import { useCollectionDetail } from '~/composables/useCollectionDetail'

export default defineNuxtRouteMiddleware(async (to) => {
  const { id } = useSlugParser(String(to.params.slug), 'Collection')
  const { data } = await useCollectionDetail(id)

  if (!data.value) {
    throw createError({ status: 404, message: 'Collection not found' })
  }

  to.meta.title = data.value.title
  to.meta.description = data.value.description
})
