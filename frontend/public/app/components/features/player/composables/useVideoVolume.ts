import { type Ref } from 'vue'
import { useEventListener } from '@vueuse/core'

export const useVideoVolume = (videoRef: Ref<HTMLVideoElement | undefined>) => {
  const volume = ref<number>(0.5)
  const muted = ref<boolean>(false)
  const lastVolume = ref<number>(volume.value)

  const apply = (): void => {
    if (videoRef.value) {
      videoRef.value.volume = volume.value
      videoRef.value.muted = muted.value
    }
  }

  const setVolume = (newVolume: number): void => {
    volume.value = newVolume
    lastVolume.value = newVolume
    muted.value = newVolume <= 0
  }

  const toggleMute = (): void => {
    muted.value = !muted.value
  }

  watch(
    volume,
    () => {
      apply()
    },
    { immediate: true }
  )

  watch(
    muted,
    () => {
      if (muted.value) {
        volume.value = 0
      } else {
        volume.value = lastVolume.value
      }

      apply()
    },
    { immediate: true }
  )

  useEventListener(videoRef.value, 'loadedmetadata', () => {
    apply()
  })

  return {
    volume,
    muted,
    setVolume,
    toggleMute
  }
}
