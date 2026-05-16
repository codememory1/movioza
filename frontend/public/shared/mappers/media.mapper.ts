import type {
  MediaDetailDto,
  MediaDetailModel,
  MediaListDto,
  MediaListFiltersDto,
  MediaListFiltersModel,
  MediaListModel,
  MediaShortDto,
  MediaShortModel
} from '#shared/types/media'
import type { MediaType } from '#shared/constants/media-type.constants'
import type { MediaKind } from '#shared/constants/media-kind.constants'
import { genreMapper } from '#shared/mappers/genre.mapper'
import { personMapper } from '#shared/mappers/person.mapper'
import { imageMapper } from '#shared/mappers/image.mapper'
import { paginationMapper } from '#shared/mappers/pagination.mapper'

export const mediaMapper = {
  toShortModel(dto: MediaShortDto): MediaShortModel {
    return {
      id: dto.id,
      slug: dto.slug,
      type: dto.type as MediaType,
      kind: dto.kind as MediaKind,
      title: dto.title,
      description: dto.description,
      poster: imageMapper.toModel(dto.poster),
      rating: dto.rating,
      duration: dto.duration,
      quality: dto.quality,
      genres: dto.genres.map(genreMapper.toShortModel),
      directors: dto.directors.map(personMapper.toModel),
      watchPath: `/watch/${dto.id}-${dto.slug}`,
      releasedAt: new Date(dto.released_at * 1000)
    }
  },

  toDetailModel(dto: MediaDetailDto): MediaDetailModel {
    return {
      ...mediaMapper.toShortModel(dto),
      logo: dto.logo,
      backdrop: imageMapper.toModel(dto.backdrop),
      alternativeTitles: dto.alternative_titles,
      country: dto.country,
      ageRestrictions: dto.age_restrictions,
      votes: dto.votes,
      episodeCount: dto.episode_count,
      seasonCount: dto.season_count,
      actors: dto.actors.map(personMapper.toModel),
      playbackId: dto.playback_id,
      uploadedAt: new Date(dto.uploaded_at * 1000),
      updatedAt: null !== dto.updated_at ? new Date(dto.updated_at * 1000) : null
    }
  },

  toListFiltersDto(model: MediaListFiltersModel): MediaListFiltersDto {
    return {
      genres: model.genres.map(Number),
      countries: model.countries.map(Number)
    }
  },

  toListFiltersModel(dto: MediaListFiltersDto): MediaListFiltersModel {
    return {
      genres: dto.genres.map(String),
      countries: dto.countries.map(String)
    }
  },

  toListModel(dto: MediaListDto): MediaListModel {
    return {
      data: dto.data.map(mediaMapper.toShortModel),
      meta: {
        pagination: paginationMapper.toModel(dto.meta.pagination)
      }
    }
  }
}
