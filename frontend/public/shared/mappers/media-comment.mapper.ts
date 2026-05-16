import type { MediaCommentsListDto } from '#shared/types/media/comment.dto'
import type { MediaCommentsListModel } from '#shared/types/media/comment.model'
import { commentMapper } from '#shared/mappers/comment.mapper'
import { paginationMapper } from '#shared/mappers/pagination.mapper'

export const mediaCommentMapper = {
  toListModel(dto: MediaCommentsListDto): MediaCommentsListModel {
    return {
      data: dto.data.map(commentMapper.toModel),
      meta: {
        pagination: paginationMapper.toModel(dto.meta.pagination)
      }
    }
  }
}
