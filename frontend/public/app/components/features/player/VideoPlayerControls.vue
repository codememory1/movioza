<template>
  <div class="video-player-controls">
    <div class="video-player-controls__left">
      <VidePlayerControlButton
        :aria-label="isPlaying ? 'Воспроизвести' : 'Пауза'"
        @click="emit('togglePlayback')"
      >
        <APlayOutlineIcon v-if="!isPlaying" />
        <APauseOutlineIcon v-else />
      </VidePlayerControlButton>

      <VideoPlayerVolume
        :muted
        :volume
        @toggle-mute="emit('toggleMute')"
        @update-volume="emit('updateVolume', $event)"
      />
    </div>
    <div class="video-player-controls__right">
      <VidePlayerControlButton
        ref="settingsButtonRef"
        aria-label="Настройки"
        @click="emit('toggleSettings')"
      >
        <ASettingsOutlineIcon />
      </VidePlayerControlButton>
      <VidePlayerControlButton aria-label="Картинка в картинке" @click="emit('togglePip')">
        <APIPOutlineIcon />
      </VidePlayerControlButton>
      <VidePlayerControlButton aria-label="Полноэкранный режим" @click="emit('toggleFullscreen')">
        <AFullscreenOutlineIcon />
      </VidePlayerControlButton>
    </div>
  </div>
</template>

<script lang="ts" setup>
import VidePlayerControlButton from '~/components/features/player/VidePlayerControlButton.vue'
import APlayOutlineIcon from '~/components/ui/icon/APlayOutlineIcon.vue'
import ASettingsOutlineIcon from '~/components/ui/icon/ASettingsOutlineIcon.vue'
import APauseOutlineIcon from '~/components/ui/icon/APauseOutlineIcon.vue'
import AFullscreenOutlineIcon from '~/components/ui/icon/AFullscreenOutlineIcon.vue'
import APIPOutlineIcon from '~/components/ui/icon/APIPOutlineIcon.vue'
import VideoPlayerVolume from '~/components/features/player/VideoPlayerVolume.vue'

export interface VideoPlayerControlsProps {
  isPlaying: boolean
  supportsPip?: boolean
  muted: boolean
  volume: number
}

export interface VideoPlayerControlsEmits {
  togglePip: []
  toggleFullscreen: []
  togglePlayback: []
  toggleSettings: []
  toggleMute: []
  updateVolume: [volume: number]
}

withDefaults(defineProps<VideoPlayerControlsProps>(), {
  supportsPip: true
})

const emit = defineEmits<VideoPlayerControlsEmits>()
const settingsButtonRef = ref<HTMLElement>()

defineExpose({
  settingsButtonRef
})
</script>

<style lang="scss">
@use './styles/VideoPlayerControls.styles' as *;
</style>
