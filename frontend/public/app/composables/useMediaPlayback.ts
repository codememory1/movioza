import { playbackService } from '#shared/services/playback.service'

export const useMediaPlayback = (playbackId: MaybeRefOrGetter<number | null | undefined>) => {
  const id = computed(() => toValue(playbackId))

  return useAsyncData(
    `media-playback-${id.value}`,
    async () => {
      if (null === id.value || !id.value) {
        return null
      }

      return await playbackService.getById(id.value)
    },
    {
      watch: [id],
      immediate: null !== id.value && undefined !== id.value
    }
  )
}
