<template>
  <div class="video-player-center-actions">
    <button
      v-if="!continueWatching"
      class="video-player-center-actions__play-btn"
      aria-label="Воспроизвести видео"
      @click="emit('play')"
    >
      <APlayRateIcon aria-hidden="true" />
    </button>
    <button
      v-else
      class="video-player-center-actions__continue-watching-btn"
      aria-label="Продолжить просмотр"
    >
      Продолжить просмотр
    </button>
  </div>
</template>

<script lang="ts" setup>
import APlayRateIcon from '~/components/ui/icon/APlayRateIcon.vue'

export interface VideoPlayerCenterActionsProps {
  continueWatching?: boolean
}

export interface VideoPlayerCenterActionsEmits {
  play: []
}

defineProps<VideoPlayerCenterActionsProps>()

const emit = defineEmits<VideoPlayerCenterActionsEmits>()
</script>

<style lang="scss">
@use './styles/VideoPlayerCenterActions.styles' as *;
</style>
