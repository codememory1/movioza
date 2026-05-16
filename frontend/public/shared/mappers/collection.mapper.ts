import type {
  CollectionDetailDto,
  CollectionDetailModel,
  CollectionMediaListDto,
  CollectionMediaListModel,
  CollectionShortDto,
  CollectionShortModel,
  CollectionsListDto,
  CollectionsListModel
} from '#shared/types/collection'
import { paginationMapper } from '#shared/mappers/pagination.mapper'
import { mediaMapper } from '#shared/mappers/media.mapper'

export const collectionMapper = {
  toShortModel(dto: CollectionShortDto): CollectionShortModel {
    return {
      id: dto.id,
      slug: dto.slug,
      title: dto.title
    }
  },

  toDetailModel(dto: CollectionDetailDto): CollectionDetailModel {
    return {
      ...collectionMapper.toShortModel(dto),
      description: dto.description,
      mediaCount: dto.media_count
    }
  },

  toListModel(dto: CollectionsListDto): CollectionsListModel {
    return {
      data: dto.data.map(collectionMapper.toDetailModel),
      meta: {
        pagination: paginationMapper.toModel(dto.meta.pagination)
      }
    }
  },

  toMediaListModel(dto: CollectionMediaListDto): CollectionMediaListModel {
    return {
      data: dto.data.map(mediaMapper.toShortModel),
      meta: {
        pagination: paginationMapper.toModel(dto.meta.pagination)
      }
    }
  }
}
