import type { CountryModel } from '#shared/types/country'
import { useNuxtApp } from '#imports'
import { countryMapper } from '#shared/mappers/country.mapper'

export const countryService = {
  async getAll(): Promise<CountryModel[]> {
    const { $api } = useNuxtApp()
    const response = await $api('/countries')

    return response.data.map(countryMapper.toModel)
  }
}
