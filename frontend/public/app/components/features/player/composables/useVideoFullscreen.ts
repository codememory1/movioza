import { useFullscreen } from '@vueuse/core'

export interface HTMLVideoElementWithFullscreen extends HTMLVideoElement {
  webkitEnterFullscreen?: () => void
  webkitEnterFullScreen?: () => void
}

export const useVideoFullscreen = (
  container: Ref<HTMLElement | undefined>,
  video: Ref<HTMLVideoElementWithFullscreen | undefined>
) => {
  const { isFullscreen, toggle: useToggle, isSupported } = useFullscreen(container)

  async function toggle(): Promise<void> {
    if (isSupported.value) {
      await useToggle()
    } else {
      if (!video.value) {
        return
      }

      if (typeof video.value.webkitEnterFullscreen === 'function') {
        video.value.webkitEnterFullscreen()

        return
      }

      if (typeof video.value.webkitEnterFullScreen === 'function') {
        video.value.webkitEnterFullScreen()

        return
      }
    }
  }

  return {
    toggle,
    isFullscreen
  }
}
