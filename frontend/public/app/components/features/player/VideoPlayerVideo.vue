<template>
  <video
    ref="videoRef"
    class="video-player-video"
    preload="none"
    playsinline
    x-webkit-playsinline
    webkit-playsinline
    :style="{ transform: `scale(${scale})` }"
  >
    Ваш браузер не поддерживает HTML5-видео.
  </video>
</template>

<script lang="ts" setup>
export interface VideoPlayerVideoProps {
  scale?: number
}

withDefaults(defineProps<VideoPlayerVideoProps>(), {
  scale: 1
})

const videoRef = ref<HTMLVideoElement>()

defineExpose({
  videoRef
})
</script>

<style lang="scss">
@use './styles/VideoPlayerVideo.styles' as *;
</style>
