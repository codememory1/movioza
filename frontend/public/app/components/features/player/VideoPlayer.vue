<template>
  <div
    ref="videoPlayerRef"
    class="video-player"
    role="region"
    aria-label="Область воспроизведения видео"
    tabindex="0"
    @keydown="onVideoPlayerKeydown"
    @pointerdown="onVideoPlayerPointerDown"
    @click="onVideoPlayerClick"
  >
    <div class="video-player__portal-root" @click.stop />

    <VideoPlayerMedia>
      <VideoPlayerBackdrop
        v-if="!hasStarted"
        :title
        :sources="backdropSources"
        :backdrop="backdrop"
      />

      <VideoPlayerVideoWrapper @click.stop>
        <VideoPlayerVideo ref="videoComponentRef" :scale="videoScale" />
        <VideoPlayerAmbient ref="ambientComponentRef" :visible="isAmbientEnabled" />
      </VideoPlayerVideoWrapper>

      <VideoPlayerCenterActions v-if="!hasStarted" @play="playback" @click.stop />

      <VideoPlayerOverlay v-if="hasStarted">
        <VideoPlayerSettingsMenu
          ref="settingsMenuRef"
          :marathon-mode="marathonMode"
          :visible="settingsMenuVisible"
          :quality-levels="qualityLevels"
          @toggle-ambient="setAmbientEnabled"
          @update-quality="updateQuality"
          @update-playback-speed="updatePlaybackRate"
          @update-scale="videoScale = $event"
          @update:marathon-mode="updateMarathonMode"
        />
        <VideoPlayerContentToolbar
          v-if="$slots.contentToolbar"
          :visible="isToolbarVisible"
          @click.stop
        >
          <slot name="contentToolbar" />
        </VideoPlayerContentToolbar>
        <VideoPlayerWatermark v-if="watermark" :link="watermarkLink" :raised="isToolbarVisible" />
        <VideoPlayerPlaybackToolbar
          ref="playbackToolbarRef"
          :isPlaying
          :currentTime
          :bufferedTime
          :duration
          :volume
          :progress-bar-markers
          :is-muted="muted"
          :supports-pip="supportsPictureInPicture"
          :visible="isToolbarVisible"
          @update-current-time="updateCurrentTime"
          @toggle-fullscreen="toggleFullscreen"
          @toggle-playback="playback"
          @toggle-pip="pip"
          @toggle-mute="toggleMute"
          @update-volume="setVolume"
          @toggle-settings="settingsMenuVisible = !settingsMenuVisible"
          @click.stop
        />
      </VideoPlayerOverlay>
    </VideoPlayerMedia>
  </div>
</template>

<script lang="ts" setup>
import VideoPlayerBackdrop from './VideoPlayerBackdrop.vue'
import VideoPlayerMedia from './VideoPlayerMedia.vue'
import VideoPlayerOverlay from './VideoPlayerOverlay.vue'
import VideoPlayerCenterActions from './VideoPlayerCenterActions.vue'
import { useVideoPlayer, type UseVideoPlayerArtwork } from './composables/useVideoPlayer'
import VideoPlayerVideoWrapper from './VideoPlayerVideoWrapper.vue'
import VideoPlayerContentToolbar from './VideoPlayerContentToolbar.vue'
import VideoPlayerAmbient from './VideoPlayerAmbient.vue'
import VideoPlayerVideo from './VideoPlayerVideo.vue'
import { useVideoBarVisibility } from './composables/useVideoBarVisibility'
import VideoPlayerSettingsMenu from './VideoPlayerSettingsMenu.vue'
import { onClickOutside } from '@vueuse/core'
import VideoPlayerPlaybackToolbar from './VideoPlayerPlaybackToolbar.vue'
import type { VideoPlayerProgressBar } from './VideoPlayerProgressBar.vue'
import VideoPlayerWatermark from './VideoPlayerWatermark.vue'
import type { MediaSourceModel } from '#shared/types/media-source'
import type { ImageModel } from '#shared/types/common'

export interface VideoPlayerProps {
  title: string
  backdropSources: MediaSourceModel[]
  backdrop: ImageModel
  playlist: string
  progressBarMarkers: VideoPlayerProgressBar[]
  watermark?: boolean
  watermarkLink?: string
}

const props = defineProps<VideoPlayerProps>()
const playlist = toRef(props, 'playlist')
const videoPlayerRef = ref<HTMLElement>()
const videoComponentRef = ref<InstanceType<typeof VideoPlayerVideo>>()
const ambientComponentRef = ref<InstanceType<typeof VideoPlayerAmbient>>()
const playbackToolbarRef = ref<InstanceType<typeof VideoPlayerPlaybackToolbar>>()
const settingsMenuRef = ref<HTMLElement>()
const videoRef = computed<HTMLVideoElement | undefined>(() => videoComponentRef.value?.videoRef)
const ambientRef = computed<HTMLCanvasElement | undefined>(
  () => ambientComponentRef.value?.ambientRef
)
const settingsButtonRef = computed<HTMLElement | undefined>(
  () => playbackToolbarRef.value?.settingsButtonRef
)
const {
  hasStarted,
  isPlaying,
  audioTracks,
  qualityLevels,
  volume,
  supportsPictureInPicture,
  currentTime,
  bufferedTime,
  duration,
  muted,
  audioTrack,
  isAmbientEnabled,
  marathonMode,
  playback,
  toggleFullscreen,
  updateAudioTrack,
  updateCurrentTime,
  pip,
  toggleMute,
  setVolume,
  setAmbientEnabled,
  updateQuality,
  updatePlaybackRate,
  updateMarathonMode,
  seekBy
} = useVideoPlayer(videoPlayerRef, videoRef, ambientRef, {
  source: playlist
})
const settingsMenuVisible = ref<boolean>(false)
const videoScale = ref<number>(1)
const canPlaybackOnContainerClick = ref<boolean>(true)
const toolbarKeepVisibleConditions = computed<boolean[]>(() => [settingsMenuVisible.value])
const { isVisible: isToolbarVisible } = useVideoBarVisibility(videoPlayerRef, videoRef, {
  keepVisibleConditions: toolbarKeepVisibleConditions
})

watch(
  () => isToolbarVisible.value,
  () => {
    if (!isToolbarVisible.value) {
      videoPlayerRef.value?.focus()
    }
  }
)

onClickOutside(
  settingsMenuRef,
  () => {
    settingsMenuVisible.value = false
  },
  {
    ignore: [settingsButtonRef]
  }
)

const onVideoPlayerKeydown = async (event: KeyboardEvent): Promise<void> => {
  if (!videoRef.value) {
    return
  }

  switch (event.code) {
    case 'Space':
      event.preventDefault()
      await playback()
      break
    case 'KeyF':
      event.preventDefault()
      await toggleFullscreen()
      break
    case 'ArrowRight':
      event.preventDefault()
      seekBy(10)
      break
    case 'ArrowLeft':
      event.preventDefault()
      seekBy(-10)
      break
  }
}

const onVideoPlayerPointerDown = (): void => {
  canPlaybackOnContainerClick.value = !settingsMenuVisible.value

  videoPlayerRef.value?.focus()
}

const onVideoPlayerClick = (): void => {
  if (canPlaybackOnContainerClick.value) {
    playback()
  }
}
</script>

<style lang="scss">
@use './styles/VideoPlayer.styles' as *;
</style>
