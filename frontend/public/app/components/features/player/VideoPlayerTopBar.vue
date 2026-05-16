<template>
  <div class="video-player-top-bar">
    <ASelect
      v-model="selectedAudioTrack"
      placeholder="Озвучка"
      :options="audioTracks"
      option-label="name"
      option-value="index"
      :prepend-icon="AAudioLinesIcon"
    />
  </div>
</template>

<script lang="ts" setup>
import type { MediaPlaylist } from 'hls.js'
import ASelect from '~/components/ui/select/ASelect.vue'
import AAudioLinesIcon from '~/components/ui/icon/AAudioLinesIcon.vue'

export interface VideoPlayerTopBarAudioTrack {
  index: number
  name: string
}

export interface VideoPlayerTopBarProps {
  visible: boolean
  audioTracks: MediaPlaylist[]
}

const props = defineProps<VideoPlayerTopBarProps>()
const audioTracks = computed<VideoPlayerTopBarAudioTrack[]>(() =>
  props.audioTracks.map((audioTrack, i) => ({
    index: i,
    name: audioTrack.name
  }))
)
const selectedAudioTrack = ref<number | null>(null)

watch(
  () => props.audioTracks,
  () => {
    const index = props.audioTracks.findIndex((audioTrack) => audioTrack.default)

    selectedAudioTrack.value = index === -1 ? null : index
  },
  { immediate: true }
)
</script>
