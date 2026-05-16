<template>
  <div class="video-player-seekbar">
    <span class="video-player-seekbar__time video-player-seekbar__time--left">
      {{ formatHMS(currentTime) }}
    </span>
    <VideoPlayerProgressBar
      :current-time
      :duration
      :buffered-time
      :markers="progressBarMarkers"
      @update-duration="emit('updateCurrentTime', $event)"
    />
    <span class="video-player-seekbar__time video-player-seekbar__time--right">
      {{ formatHMS(duration) }}
    </span>
  </div>
</template>

<script lang="ts" setup>
import { formatHMS } from '~/utils/format'
import VideoPlayerProgressBar, {
  type VideoPlayerProcessBarMarker
} from '~/components/features/player/VideoPlayerProgressBar.vue'

export interface VideoPlayerSeekbarProps {
  currentTime: number
  duration: number
  bufferedTime: number
  progressBarMarkers: VideoPlayerProcessBarMarker[]
}

export interface VideoPlayerSeekbarEmits {
  updateCurrentTime: [newDuration: number]
}

defineProps<VideoPlayerSeekbarProps>()

const emit = defineEmits<VideoPlayerSeekbarEmits>()
</script>

<style lang="scss">
@use './styles/VideoPlayerSeekbar.styles' as *;
</style>
