<template>
  <canvas
    ref="ambientRef"
    class="video-player-ambient"
    aria-hidden="true"
    :class="{ 'video-player-ambient--visible': visible }"
  />
</template>

<script lang="ts" setup>
export interface VideoPlayerAmbientProps {
  visible: boolean
}

defineProps<VideoPlayerAmbientProps>()

const ambientRef = ref<HTMLCanvasElement>()

defineExpose({
  ambientRef
})
</script>

<style lang="scss">
@use './styles/VideoPlayerAmbient.styles' as *;
</style>
