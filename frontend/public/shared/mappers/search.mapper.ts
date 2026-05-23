import type { SearchMediaListDto, SearchMediaListModel } from '#shared/types/search'
import { mediaMapper } from '#shared/mappers/media.mapper'
import { paginationMapper } from '#shared/mappers/pagination.mapper'

export const searchMapper = {
  toMediaListModel(dto: SearchMediaListDto): SearchMediaListModel {
    return {
      data: dto.data.map(mediaMapper.toShortModel),
      meta: {
        pagination: paginationMapper.toModel(dto.meta.pagination)
      }
    }
  }
}
