<template>
  <VideoPlayer
    :title="media.title"
    :backdrop-sources="[]"
    :backdrop="media.backdrop"
    :playlist="playback.playlist"
    :progress-bar-markers="[]"
    :watermark
    :watermark-link
  >
    <template v-if="$slots.contentToolbar" #contentToolbar>
      <slot name="contentToolbar" />
    </template>
  </VideoPlayer>
</template>

<script lang="ts" setup>
import VideoPlayer from '~/components/features/player/VideoPlayer.vue'
import type { EpisodeModel, MediaDetailModel, SeasonModel } from '#shared/types/media'
import type { PlaybackModel } from '#shared/types/playback'
import { useMediaSessionMetadata } from '~/components/features/player/composables/useMediaSessionMetadata'

export interface MediaVideoPlayerProps {
  media: MediaDetailModel
  playback: PlaybackModel
  season?: SeasonModel
  episode?: EpisodeModel
  watermark?: boolean
  watermarkLink?: string
}

const props = defineProps<MediaVideoPlayerProps>()
const title = computed<string>(() => {
  if (props.episode) {
    return `${props.media.title} — ${props.episode.number} серия`
  }

  return props.media.title
})
const album = computed<string>(() => {
  if (props.season) {
    return `${props.season.number} сезон`
  }

  return String(props.media.releasedAt.getFullYear())
})
const poster = computed<string>(() => props.media.poster)

useMediaSessionMetadata({ title, album, poster })
</script>
