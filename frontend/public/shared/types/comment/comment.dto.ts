export interface CommentDto {
  id: string
  sender_name: string
  message: string
  likes_count: number
  dislikes_count: number
  replies_count: number
  liked: boolean
  disliked: boolean
  created_at: number
}
