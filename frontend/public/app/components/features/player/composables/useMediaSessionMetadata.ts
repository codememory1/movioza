export interface UseMediaSessionMetadataOptions {
  title: MaybeRef<string>
  album: MaybeRef<string>
  poster: MaybeRef<string>
}

export const useMediaSessionMetadata = (options: UseMediaSessionMetadataOptions) => {
  const config = useRuntimeConfig()

  const setMetadata = (): void => {
    if (!('mediaSession' in navigator) || !('MediaMetadata' in window)) {
      return
    }

    navigator.mediaSession.metadata = new MediaMetadata({
      title: toValue(options.title),
      artist: config.public.appShortTitle,
      album: toValue(options.album),
      artwork: [
        {
          src: toValue(options.poster) || '',
          sizes: '512x512',
          type: 'image/jpeg'
        }
      ]
    })
  }

  onMounted(() => {
    setMetadata()
  })

  watch(
    () => [toValue(options.title), toValue(options.album), toValue(options.poster)],
    () => {
      setMetadata()
    }
  )
}
