import type { PlaybackModel } from '#shared/types/playback'
import { useNuxtApp } from '#imports'
import { playbackMapper } from '#shared/mappers/playback.mapper'

export const playbackService = {
  async getById(id: number): Promise<PlaybackModel> {
    const { $api } = useNuxtApp()
    const response = await $api(`/playbacks/${id}`)

    return playbackMapper.toModel(response.data)
  }
}
