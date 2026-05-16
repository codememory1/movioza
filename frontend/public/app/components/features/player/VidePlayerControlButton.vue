<template>
  <button class="video-player-control-btn" :class="{ active }" @click="emit('click')">
    <slot />
  </button>
</template>

<script lang="ts" setup>
export interface VideoPlayerControlButtonProps {
  active?: boolean
}

export interface VideoPlayerControlButtonEmits {
  click: []
}

defineProps<VideoPlayerControlButtonProps>()

const emit = defineEmits<VideoPlayerControlButtonEmits>()
</script>

<style lang="scss">
@use './styles/VideoPlayerControlButton.styles' as *;
</style>
