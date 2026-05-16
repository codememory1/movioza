import type { CommentModel } from '#shared/types/comment'
import { useNuxtApp } from '#imports'
import { commentMapper } from '#shared/mappers/comment.mapper'

export const commentService = {
  async getReplies(commentId: string): Promise<CommentModel[]> {
    const { $api } = useNuxtApp()
    const response = await $api(`/comments/${commentId}/replies`)

    return response.data.map(commentMapper.toModel)
  }
}
