<template>
  <div
    class="video-player-content-toolbar"
    role="toolbar"
    aria-label="Панель управления контентом"
    :class="{ 'video-player-content-toolbar--visible': visible }"
  >
    <slot />
  </div>
</template>

<script lang="ts" setup>
export interface VideoPlayerContentToolbarProps {
  visible?: boolean
}

defineProps<VideoPlayerContentToolbarProps>()
</script>

<style lang="scss">
@use './styles/VideoPlayerContentToolbar.styles' as *;
</style>
