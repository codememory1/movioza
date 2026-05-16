import type { GenreModel } from '#shared/types/genre'
import { useNuxtApp } from '#imports'
import { genreMapper } from '#shared/mappers/genre.mapper'
import { withQuery } from '#shared/utils/url.utils'

export const genreService = {
  async all(limit?: number): Promise<GenreModel[]> {
    const { $api } = useNuxtApp()
    const response = await $api(withQuery('/genres', { limit }))

    return response.data.map(genreMapper.toModel)
  },

  async featured(limit?: number): Promise<GenreModel[]> {
    const { $api } = useNuxtApp()

    const response = await $api(withQuery('/featured-genres', { limit }))

    return response.data.map(genreMapper.toModel)
  }
}
