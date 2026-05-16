import { countryService } from '#shared/services/country.service'

export const useCountries = () => {
  return useAsyncData('countries', () => countryService.getAll())
}
