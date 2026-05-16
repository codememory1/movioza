import type {
  MediaDetailModel,
  MediaListFiltersModel,
  MediaListModel,
  MediaShortModel,
  SeasonModel
} from '#shared/types/media'
import { useNuxtApp } from '#imports'
import { mediaMapper } from '#shared/mappers/media.mapper'
import { seasonMapper } from '#shared/mappers/season.mapper'
import type { MediaCommentsListModel } from '#shared/types/media/comment.model'
import { mediaCommentMapper } from '#shared/mappers/media-comment.mapper'
import { MEDIA_TYPES, type MediaType } from '#shared/constants/media-type.constants'
import type { MediaKind } from '#shared/constants/media-kind.constants'

export const mediaService = {
  async getDetailById(id: number): Promise<MediaDetailModel> {
    const { $api } = useNuxtApp()
    const response = await $api(`/media/${id}`)

    return mediaMapper.toDetailModel(response.data)
  },

  async getSeasons(mediaId: number): Promise<SeasonModel> {
    const { $api } = useNuxtApp()
    const response = await $api(`/media/${mediaId}/seasons`)

    return response.data.map(seasonMapper.toModel)
  },

  async getSeason(mediaId: number, seasonNumber: number): Promise<SeasonModel> {
    const { $api } = useNuxtApp()
    const response = await $api(`/media/${mediaId}/seasons/${seasonNumber}`)

    return seasonMapper.toModel(response.data)
  },

  async getComments(mediaId: number, page: number = 1): Promise<MediaCommentsListModel> {
    const { $api } = useNuxtApp()
    const response = await $api(`/media/${mediaId}/comments?page=${page}`)

    return mediaCommentMapper.toListModel(response)
  },

  async getFeatured(): Promise<MediaShortModel> {
    const { $api } = useNuxtApp()
    const response = await $api('/media/featured')

    return mediaMapper.toShortModel(response.data)
  },

  async getMoviesList(
    page: number,
    limit: number,
    filters?: MediaListFiltersModel
  ): Promise<MediaListModel> {
    const { $api } = useNuxtApp()
    const response = await $api(
      withQuery('/media', {
        type: MEDIA_TYPES.movie.apiType,
        page,
        limit,
        filters
      })
    )

    return mediaMapper.toListModel(response)
  },

  async getSeriesList(
    page: number,
    limit: number,
    filters?: MediaListFiltersModel
  ): Promise<MediaListModel> {
    const { $api } = useNuxtApp()
    const response = await $api(
      withQuery('/media', {
        type: MEDIA_TYPES.series.apiType,
        page,
        limit,
        filters
      })
    )

    return mediaMapper.toListModel(response)
  }
}
