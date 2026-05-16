export interface CommentModel {
  id: string
  senderName: string
  message: string
  likesCount: number
  dislikesCount: number
  repliesCount: number
  liked: boolean
  disliked: boolean
  createdAt: Date
}
