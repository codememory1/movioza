import type { SearchMediaListModel } from '#shared/types/search'
import type { MediaListFiltersModel } from '#shared/types/media'
import { useNuxtApp } from '#imports'
import { withQuery } from '#shared/utils/url.utils'
import { searchMapper } from '#shared/mappers/search.mapper'

export const searchService = {
  async searchMedia(
    query: string | null,
    page: number,
    limit: number,
    filters?: MediaListFiltersModel
  ): Promise<SearchMediaListModel> {
    const { $api } = useNuxtApp()
    const response = await $api(withQuery('/search/media', { query, page, limit, filters }))

    return searchMapper.toMediaListModel(response)
  }
}
