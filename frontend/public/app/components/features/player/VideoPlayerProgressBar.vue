<template>
  <ARange
    ref="progressBarRef"
    :value="currentTime"
    :max="duration"
    @update-dragging="isDragging = $event"
    @update-value="newDuration = $event"
    @release="onRelease"
    class="video-player-progress-bar"
  >
    <template #appendBar>
      <ARangeHoverBar :is-hovering="true" :ratio="bufferedRation" />

      <div
        v-for="marker in markers"
        :key="marker.time"
        class="video-player-progress-bar__marker"
        :style="{ left: `${(marker.time / props.duration) * 100}%` }"
      />
    </template>
    <template #tooltip="{ value, left }">
      <VideoPlayerProgressBarTooltip :duration="value" :left />
    </template>
  </ARange>
</template>

<script lang="ts" setup>
import ARange from '~/components/ui/range/ARange.vue'
import ARangeHoverBar from '~/components/ui/range/ARangeHoverBar.vue'
import VideoPlayerProgressBarTooltip from '~/components/features/player/VideoPlayerProgressBarTooltip.vue'

export interface VideoPlayerProgressBar {
  time: number
}

export interface VideoProcessBarProps {
  duration: number
  currentTime: number
  bufferedTime: number
  markers: VideoPlayerProgressBar[]
}

export interface VideoPlayerProgressBarEmits {
  (e: 'updateDuration', newDuration: number): void
}

const props = defineProps<VideoProcessBarProps>()
const emit = defineEmits<VideoPlayerProgressBarEmits>()
const isDragging = ref<boolean>(false)
const newDuration = ref<number>(props.currentTime)
const pendingDuration = ref<number | null>(null)
const currentTime = computed<number>(() => {
  if (isDragging.value) {
    return newDuration.value
  }

  if (null !== pendingDuration.value) {
    return pendingDuration.value
  }

  return props.currentTime
})

const bufferedRation = computed<number>(() => {
  if (props.duration <= 0 || props.bufferedTime <= 0) {
    return 0
  }

  return Math.min((props.bufferedTime / props.duration) * 100, 100)
})

watch(
  () => props.currentTime,
  () => {
    if (null !== pendingDuration.value) {
      pendingDuration.value = null
    }
  }
)

const onRelease = (value: number): void => {
  pendingDuration.value = value

  emit('updateDuration', value)
}
</script>

<style lang="scss">
@use './styles/VideoPlayerProgressBar.styles' as *;
</style>
