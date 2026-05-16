import { commentService } from '#shared/services/comment.service'

export const useCommentReplies = (id: MaybeRefOrGetter<string>) => {
  const resolvedId = computed<string>(() => toValue(id))

  const { data, pending, error, execute } = useAsyncData(
    `comment-${resolvedId.value}-replies`,
    () => commentService.getReplies(resolvedId.value),
    { immediate: false }
  )

  return {
    data,
    pending,
    error,
    execute
  }
}
