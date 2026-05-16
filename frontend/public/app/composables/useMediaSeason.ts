import type { MaybeRefOrGetter } from 'vue'
import { mediaService } from '#shared/services/media.service'

export const useMediaSeason = (
  mediaId: MaybeRefOrGetter<number>,
  seasonNumber: MaybeRefOrGetter<number | undefined | null>
) => {
  const resolvedMediaId = computed(() => toValue(mediaId))
  const resolvedSeasonNumber = computed(() => toValue(seasonNumber))

  return useAsyncData(
    `media-${resolvedMediaId.value}-season-${resolvedSeasonNumber.value}`,
    async () => {
      if (resolvedSeasonNumber.value === null || resolvedSeasonNumber.value === undefined) {
        return null
      }

      return await mediaService.getSeason(resolvedMediaId.value, resolvedSeasonNumber.value)
    },
    {
      watch: [resolvedSeasonNumber],
      immediate: resolvedSeasonNumber.value !== null && resolvedSeasonNumber.value !== undefined
    }
  )
}
