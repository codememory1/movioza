<template>
  <span class="video-player__progress-bar-tooltip" :style="{ left: `${left}%` }">
    {{ formatHMS(duration) }}
  </span>
</template>

<script lang="ts" setup>
import { formatHMS } from '~/utils/format'

export interface VideoPlayerTooltipDurationProps {
  duration: number
  left: number
}

defineProps<VideoPlayerTooltipDurationProps>()
</script>

<style lang="scss">
@use './styles/VideoPlayerTooltipDuration.styles' as *;
</style>
