import type { GenreDto, GenreShortDto, GenreShortModel, GenreModel } from '#shared/types/genre'

export const genreMapper = {
  toShortModel(dto: GenreShortDto): GenreShortModel {
    return {
      id: dto.id,
      slug: dto.slug,
      name: dto.name
    }
  },

  toModel(dto: GenreDto): GenreModel {
    return {
      ...genreMapper.toShortModel(dto),
      subtitle: dto.subtitle,
      emoji: dto.emoji
    }
  }
}
