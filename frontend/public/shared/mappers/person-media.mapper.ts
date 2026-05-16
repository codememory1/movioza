import type { PersonMediaListDto, PersonMediaListModel } from '#shared/types/person'
import { mediaMapper } from '#shared/mappers/media.mapper'
import { paginationMapper } from '#shared/mappers/pagination.mapper'

export const personMediaMapper = {
  toListModel(dto: PersonMediaListDto): PersonMediaListModel {
    return {
      data: dto.data.map(mediaMapper.toShortModel),
      meta: {
        pagination: paginationMapper.toModel(dto.meta.pagination)
      }
    }
  }
}
