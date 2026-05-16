import type { PersonShortDto, PersonDto, PersonShortModel, PersonModel } from '#shared/types/person'
import { imageMapper } from '#shared/mappers/image.mapper'

export const personMapper = {
  toShortModel(dto: PersonShortDto): PersonShortModel {
    return {
      id: dto.id,
      slug: dto.slug,
      name: dto.name
    }
  },

  toModel(dto: PersonDto): PersonModel {
    return {
      ...personMapper.toShortModel(dto),
      photo: null !== dto.photo ? imageMapper.toModel(dto.photo) : null
    }
  }
}
