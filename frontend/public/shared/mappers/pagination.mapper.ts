import type { PaginationMetaDto, PaginationMetaModel } from '#shared/types/common'

export const paginationMapper = {
  toModel(dto: PaginationMetaDto): PaginationMetaModel {
    return {
      page: dto.page,
      limit: dto.limit,
      totalPages: dto.total_pages
    }
  }
}
