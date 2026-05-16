import type { SeasonDto, SeasonModel, SeasonShortDto, SeasonShortModel } from '#shared/types/media'
import { episodeMapper } from '#shared/mappers/episode.mapper'
import { imageMapper } from '#shared/mappers/image.mapper'

export const seasonMapper = {
  toShortModel(dto: SeasonShortDto): SeasonShortModel {
    return {
      number: dto.number,
      title: dto.title,
      description: dto.description,
      episodeCount: dto.episode_count,
      watchSegment: `sezon-${dto.number}`,
      releasedAt: new Date(dto.released_at * 1000)
    }
  },

  toModel(dto: SeasonDto): SeasonModel {
    return {
      ...seasonMapper.toShortModel(dto),
      backdrop: null !== dto.backdrop ? imageMapper.toModel(dto.backdrop) : null,
      episodes: dto.episodes.map(episodeMapper.toModel),
      uploadedAt: new Date(dto.uploaded_at * 1000),
      updatedAt: null !== dto.updated_at ? new Date(dto.updated_at * 1000) : null
    }
  }
}
