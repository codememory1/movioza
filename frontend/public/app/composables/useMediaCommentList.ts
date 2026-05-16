import { mediaService } from '#shared/services/media.service'
import type { CommentModel } from '#shared/types/comment'

export const useMediaCommentList = async (mediaId: MaybeRefOrGetter<number>) => {
  const resolvedMediaId = computed<number>(() => toValue(mediaId))
  const { data } = await useAsyncData(`media-${resolvedMediaId.value}-comments`, () =>
    mediaService.getComments(resolvedMediaId.value)
  )
  const items = ref<CommentModel[]>(data.value?.data || [])
  const page = ref<number>(data.value?.meta.pagination.page || 1)
  const totalPages = computed<number>(() => data.value?.meta.pagination.totalPages || 1)
  const hasMore = computed<boolean>(() => page.value < totalPages.value)
  const isPendingMore = ref<boolean>(false)

  watch(data, () => {
    items.value = data.value?.data || []
  })

  const loadMore = async () => {
    if (hasMore.value) {
      isPendingMore.value = true

      try {
        const response = await mediaService.getComments(resolvedMediaId.value, page.value + 1)

        items.value.push(...response.data)

        page.value++
      } finally {
        isPendingMore.value = false
      }
    }
  }

  return {
    items,
    hasMore,
    isPendingMore,
    loadMore
  }
}
