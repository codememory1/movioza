<template>
  <div class="video-player__volume">
    <button
      class="video-player__volume-off-btn"
      aria-label="Выключить звук"
      @click="emit('toggleMute')"
    >
      <AVolumeMutedOutlineIcon v-if="volume === 0 || muted" />
      <AVolumeMinOutlineIcon v-else-if="volume > 0 && volume <= 0.5" />
      <AVolumeMaxOutlineIcon v-else />
    </button>

    <ARange
      class="video-player__volume-range"
      :value="volume * 100"
      :point="false"
      :max="100"
      @update-value="onUpdateValue"
    />
  </div>
</template>

<script lang="ts" setup>
import AVolumeMaxOutlineIcon from '~/components/ui/icon/AVolumeMaxOutlineIcon.vue'
import AVolumeMinOutlineIcon from '~/components/ui/icon/AVolumeMinOutlineIcon.vue'
import AVolumeMutedOutlineIcon from '~/components/ui/icon/AVolumeMutedOutlineIcon.vue'
import ARange from '~/components/ui/range/ARange.vue'

export interface VideoPlayerVolumeProps {
  volume: number
  muted: boolean
}

export interface VideoPlayerVolumeEmits {
  (e: 'updateVolume', volume: number): void
  (e: 'toggleMute'): void
}

defineProps<VideoPlayerVolumeProps>()

const emit = defineEmits<VideoPlayerVolumeEmits>()

const onUpdateValue = (value: number): void => {
  emit('updateVolume', value / 100)
}
</script>

<style lang="scss">
@use './styles/VideoPlayerVolume.styles' as *;
</style>
