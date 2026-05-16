<template>
  <div
    class="video-player-playback-toolbar"
    role="toolbar"
    aria-label="Панель управления воспроизведением"
    :class="{ 'video-player-playback-toolbar--visible': visible }"
  >
    <VideoPlayerSeekbar
      :current-time
      :buffered-time
      :duration
      :progress-bar-markers
      @update-current-time="emit('updateCurrentTime', $event)"
    />
    <VideoPlayerControls
      ref="controlsRef"
      :is-playing
      :supports-pip
      :volume
      :muted="isMuted"
      @toggle-pip="emit('togglePip')"
      @toggle-fullscreen="emit('toggleFullscreen')"
      @toggle-playback="emit('togglePlayback')"
      @toggle-settings="emit('toggleSettings')"
      @toggle-mute="emit('toggleMute')"
      @update-volume="emit('updateVolume', $event)"
    />
  </div>
</template>

<script lang="ts" setup>
import VideoPlayerControls from '~/components/features/player/VideoPlayerControls.vue'
import type { VideoPlayerProcessBarMarker } from '~/components/features/player/VideoPlayerProgressBar.vue'
import VideoPlayerSeekbar from '~/components/features/player/VideoPlayerSeekbar.vue'
import VideoPlayerWatermark from '~/components/features/player/VideoPlayerWatermark.vue'

export interface VideoPlayerPlaybackToolbarProps {
  isPlaying: boolean
  isMuted: boolean
  supportsPip: boolean
  volume: number
  currentTime: number
  duration: number
  bufferedTime: number
  visible?: boolean
  progressBarMarkers: VideoPlayerProcessBarMarker[]
}

export interface VideoPlayerPlaybackToolbarEmits {
  togglePip: []
  toggleFullscreen: []
  togglePlayback: []
  toggleSettings: []
  toggleMute: []
  updateCurrentTime: [newTime: number]
  updateVolume: [volume: number]
}

withDefaults(defineProps<VideoPlayerPlaybackToolbarProps>(), {
  visible: false
})

const emit = defineEmits<VideoPlayerPlaybackToolbarEmits>()
const controlsRef = ref<InstanceType<typeof VideoPlayerControls>>()
const settingsButtonRef = computed<HTMLElement | undefined>(
  () => controlsRef.value?.settingsButtonRef
)

defineExpose({
  settingsButtonRef
})
</script>

<style lang="scss">
@use './styles/VideoPlayerPlaybackToolbar.styles' as *;
</style>
