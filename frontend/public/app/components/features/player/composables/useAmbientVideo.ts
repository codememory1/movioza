export const useAmbientVideo = (
  videoRef: Ref<HTMLVideoElement | undefined>,
  canvasRef: Ref<HTMLCanvasElement | undefined>
) => {
  let ambientFrameId: number | null = null
  let lastDrawTime = 0
  const enabled = ref<boolean>(true)

  const startLoop = (): void => {
    if (ambientFrameId !== null) {
      return
    }

    const loop = (time: number): void => {
      if (!enabled.value) {
        stopLoop()

        return
      }

      const canvas = canvasRef.value
      const video = videoRef.value
      const videoRect = video?.getBoundingClientRect()

      if (!canvas || !video || !videoRect) {
        stopLoop()

        return
      }

      const ctx = canvas.getContext('2d')

      if (!ctx) {
        stopLoop()

        return
      }

      if (time - lastDrawTime >= 1000 / 30) {
        if (video.readyState >= 2 && !video.paused && !video.ended) {
          ctx.drawImage(video, 0, 0, videoRect.width, videoRect.height, 0, 0, 300, 150)
        }

        lastDrawTime = time
      }

      ambientFrameId = requestAnimationFrame(loop)
    }

    ambientFrameId = requestAnimationFrame(loop)
  }

  const stopLoop = (): void => {
    if (ambientFrameId !== null) {
      cancelAnimationFrame(ambientFrameId)

      ambientFrameId = null
    }
  }

  const setEnabled = (value: boolean): void => {
    enabled.value = value
  }

  watch(
    () => enabled.value,
    () => {
      if (window) {
        enabled.value ? startLoop() : stopLoop()
      }
    },
    { immediate: true }
  )

  onBeforeUnmount(() => {
    stopLoop()
  })

  return {
    enabled,
    startLoop,
    stopLoop,
    setEnabled
  }
}
