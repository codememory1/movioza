<template>
  <MediaVideoPlayer :media :playback :watermark :watermark-link class="movie-video-player" />
</template>

<script lang="ts" setup>
import MediaVideoPlayer from '~/components/features/player/MediaVideoPlayer.vue'
import type { MediaDetailModel } from '#shared/types/media'
import type { PlaybackModel } from '#shared/types/playback'

export interface MovieVideoPlayerProps {
  media: MediaDetailModel
  playback: PlaybackModel
  watermark?: boolean
  watermarkLink?: string
}

defineProps<MovieVideoPlayerProps>()
</script>
