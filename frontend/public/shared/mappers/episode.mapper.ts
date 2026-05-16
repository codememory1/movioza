import type {
  EpisodeShortDto,
  EpisodeDto,
  EpisodeShortModel,
  EpisodeModel
} from '#shared/types/media'
import { mediaSourceMapper } from '#shared/mappers/media-source.mapper'
import { imageMapper } from '#shared/mappers/image.mapper'

export const episodeMapper = {
  toShortModel(dto: EpisodeShortDto): EpisodeShortModel {
    return {
      number: dto.number,
      title: dto.title,
      description: dto.description,
      duration: dto.duration,
      watchSegment: `seriya-${dto.number}`,
      releasedAt: new Date(dto.released_at * 1000)
    }
  },

  toModel(dto: EpisodeDto): EpisodeModel {
    return {
      ...episodeMapper.toShortModel(dto),
      previewSources: dto.preview_sources.map(mediaSourceMapper.toModel),
      preview: null !== dto.preview ? imageMapper.toModel(dto.preview) : null,
      playbackId: dto.playback_id,
      uploadedAt: new Date(dto.uploaded_at * 1000),
      updatedAt: null !== dto.updated_at ? new Date(dto.updated_at * 1000) : null
    }
  }
}
