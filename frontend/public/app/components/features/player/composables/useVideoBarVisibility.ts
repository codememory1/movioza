import { type Ref } from 'vue'
import { useEventListener } from '@vueuse/core'
import debounce from 'lodash/debounce'

export interface UseVideoBarVisibilityOptions {
  keepVisibleConditions?: Ref<boolean[]>
  delay?: number
}

export const useVideoBarVisibility = (
  containerRef: Ref<HTMLElement | undefined>,
  videoRef: Ref<HTMLVideoElement | undefined>,
  options?: UseVideoBarVisibilityOptions
) => {
  const isPlaying = ref<boolean>(false)
  const pointerDown = ref<boolean>(false)
  const pointerIdle = ref<boolean>(true)
  const isVisible = ref<boolean>(true)
  const shouldKeepVisible = computed<boolean>(() => {
    const values = [
      ...(options?.keepVisibleConditions?.value ?? []),
      !isPlaying.value,
      pointerDown.value,
      !pointerIdle.value
    ]

    return values.some(Boolean)
  })

  const hideDelayed = debounce(() => {
    if (!shouldKeepVisible.value) {
      isVisible.value = false
    }
  }, options?.delay ?? 5000)

  watch(
    shouldKeepVisible,
    () => {
      if (shouldKeepVisible.value) {
        isVisible.value = true

        hideDelayed.cancel()

        return
      }

      hideDelayed()
    },
    { immediate: true }
  )

  const resetPointerIdle = debounce(() => {
    pointerIdle.value = true
  }, 500)

  useEventListener(containerRef, 'pointermove', () => {
    pointerIdle.value = false

    resetPointerIdle()
  })

  useEventListener(containerRef, 'pointerdown', () => {
    pointerDown.value = true
  })

  useEventListener(containerRef, 'pointerup', () => {
    pointerDown.value = false
  })

  useEventListener(videoRef, 'play', () => {
    isPlaying.value = true
  })

  useEventListener(videoRef, 'pause', () => {
    isPlaying.value = false
  })

  return {
    isVisible,
    shouldKeepVisible
  }
}
