import type { CountryDto, CountryModel } from '#shared/types/country'

export const countryMapper = {
  toModel(dto: CountryDto): CountryModel {
    return {
      id: dto.id,
      code: dto.code,
      name: dto.name
    }
  }
}
