import type { CommentDto, CommentModel } from '#shared/types/comment'

export const commentMapper = {
  toModel(dto: CommentDto): CommentModel {
    return {
      id: dto.id,
      senderName: dto.sender_name,
      message: dto.message,
      likesCount: dto.likes_count,
      dislikesCount: dto.dislikes_count,
      repliesCount: dto.replies_count,
      liked: dto.liked,
      disliked: dto.disliked,
      createdAt: new Date(dto.created_at * 1000)
    }
  }
}
