import { type Ref } from 'vue'
import Hls, { type Level, type MediaPlaylist } from 'hls.js'
import { useEventListener, useMediaControls } from '@vueuse/core'
import { useVideoFullscreen } from '~/components/features/player/composables/useVideoFullscreen'
import { useVideoVolume } from '~/components/features/player/composables/useVideoVolume'
import { useAmbientVideo } from '~/components/features/player/composables/useAmbientVideo'

export interface UseVideoPlayerArtwork {
  src: string
  type?: string
  sizes?: string
}

export interface UseVideoPlayerOptions {
  source: MaybeRef<string>
}

export const useVideoPlayer = (
  containerRef: Ref<HTMLElement | undefined>,
  videoRef: Ref<HTMLVideoElement | undefined>,
  ambientCanvasRef: Ref<HTMLCanvasElement | undefined>,
  options: UseVideoPlayerOptions
) => {
  const { supportsPictureInPicture, togglePictureInPicture } = useMediaControls(videoRef)
  const { isFullscreen, toggle: toggleFullscreen } = useVideoFullscreen(containerRef, videoRef)
  const { volume, muted, toggleMute, setVolume } = useVideoVolume(videoRef)
  const { enabled: isAmbientEnabled, setEnabled: setAmbientEnabled } = useAmbientVideo(
    videoRef,
    ambientCanvasRef
  )
  const hls = new Hls({
    capLevelToPlayerSize: true,
    renderTextTracksNatively: true
  })
  const isManifestParsing = ref<boolean>(true)
  const qualityLevels = shallowRef<Level[]>([])
  const audioTracks = shallowRef<MediaPlaylist[]>([])
  const audioTrack = ref<number | null>(null)
  const hasStarted = ref<boolean>(false)
  const isPlaying = ref<boolean>(false)
  const currentTime = ref<number>(0)
  const duration = ref<number>(0)
  const bufferedTime = ref<number>(0)
  const marathonMode = ref<boolean>(true)

  onMounted(() => {
    if (!videoRef.value) {
      return
    }

    hls.loadSource(toValue(options.source))
    hls.attachMedia(videoRef.value!)

    hls.on(Hls.Events.MANIFEST_PARSED, () => {
      qualityLevels.value = hls.levels
      isManifestParsing.value = false
    })

    hls.on(Hls.Events.AUDIO_TRACKS_UPDATED, (_, data) => {
      audioTracks.value = data.audioTracks
      audioTrack.value = data.audioTracks.findIndex((track) => track.default)
    })
  })

  onBeforeUnmount(() => {
    hls.destroy()
  })

  watch(
    () => toValue(options.source),
    (newSource) => {
      if (!videoRef.value || !newSource) {
        return
      }

      hls.stopLoad()
      hls.detachMedia()

      hls.loadSource(newSource)
      hls.attachMedia(videoRef.value)

      isPlaying.value = false
      currentTime.value = 0
    }
  )

  const play = async (): Promise<void> => {
    if (!videoRef.value) {
      return
    }

    await videoRef.value.play()

    isPlaying.value = true
  }

  const pause = (): void => {
    if (!videoRef.value) {
      return
    }

    videoRef.value.pause()

    isPlaying.value = false
  }

  const playback = async (): Promise<void> => {
    if (!videoRef.value) {
      return
    }

    if (videoRef.value.paused) {
      await play()
    } else {
      pause()
    }
  }
  const pip = async (): Promise<void> => {
    if (supportsPictureInPicture) {
      await togglePictureInPicture()
    }
  }

  const updateQuality = (level: number, autoLevelCapping: number = -1): void => {
    hls.nextLevel = level
    hls.autoLevelCapping = autoLevelCapping
  }

  const updatePlaybackRate = (rate: number): void => {
    videoRef.value!.playbackRate = rate
  }

  const updateCurrentTime = (newTime: number): void => {
    videoRef.value!.currentTime = newTime
    currentTime.value = newTime
  }

  const updateAudioTrack = (index: number | null): void => {
    audioTrack.value = index

    if (null !== index) {
      hls.audioTrack = index
    }
  }

  const updateMarathonMode = (enabled: boolean): void => {
    marathonMode.value = enabled
  }

  const seekBy = (delta: number): void => {
    updateCurrentTime(Math.min(duration.value, Math.max(0, currentTime.value + delta)))
  }

  const onPlay = (): void => {
    hasStarted.value = true
    isPlaying.value = true
  }

  const onPause = (): void => {
    isPlaying.value = false
  }

  const onTimeUpdate = (event: Event): void => {
    currentTime.value = (event.target as HTMLVideoElement).currentTime
  }

  const onLoadedMetadata = (): void => {
    duration.value = videoRef.value!.duration
  }

  const onDurationChange = (): void => {
    duration.value = videoRef.value!.duration
  }

  const onProgress = (): void => {
    const video = videoRef.value

    if (!video || !video.duration) {
      return
    }

    if (video.buffered.length === 0) {
      return
    }

    const bufferedEnd = video.buffered.end(video.buffered.length - 1)

    bufferedTime.value = (bufferedEnd / video.duration) * 100
  }

  useEventListener(videoRef, 'play', onPlay)
  useEventListener(videoRef, 'pause', onPause)
  useEventListener(videoRef, 'timeupdate', onTimeUpdate)
  useEventListener(videoRef, 'loadedmetadata', onLoadedMetadata)
  useEventListener(videoRef, 'durationchange', onDurationChange)
  useEventListener(videoRef, 'progress', onProgress)

  return {
    isManifestParsing,
    qualityLevels,
    audioTracks,
    audioTrack,
    supportsPictureInPicture,
    isFullscreen,
    hasStarted,
    isPlaying,
    currentTime,
    duration,
    bufferedTime,
    volume,
    muted,
    isAmbientEnabled,
    marathonMode,
    setAmbientEnabled,
    toggleMute,
    setVolume,
    toggleFullscreen,
    pip,
    play,
    pause,
    playback,
    updateQuality,
    updatePlaybackRate,
    updateCurrentTime,
    updateAudioTrack,
    updateMarathonMode,
    seekBy
  }
}
