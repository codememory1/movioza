<template>
  <MediaVideoPlayer
    :media
    :season
    :episode
    :playback
    :watermark
    :watermark-link
    class="series-video-player"
  >
    <template #contentToolbar>
      <ASelect
        :model-value="episode.number"
        placeholder="Выбор серии"
        :options="season.episodes"
        option-value="number"
        option-label="title"
        :prepend-icon="AListVideoOutlineIcon"
        class="series-video-player__select-episode"
        @update:model-value="onUpdateEpisode"
      />
    </template>
  </MediaVideoPlayer>
</template>

<script lang="ts" setup>
import MediaVideoPlayer from '~/components/features/player/MediaVideoPlayer.vue'
import type { EpisodeModel, MediaDetailModel, SeasonModel } from '#shared/types/media'
import type { PlaybackModel } from '#shared/types/playback'
import AListVideoOutlineIcon from '~/components/ui/icon/AListVideoOutlineIcon.vue'
import ASelect from '~/components/ui/select/ASelect.vue'

export interface SeriesVideoPlayerProps {
  media: MediaDetailModel
  season: SeasonModel
  episode: EpisodeModel
  playback: PlaybackModel
  watermark?: boolean
  watermarkLink?: string
}

export interface SeriesVideoPlayerEmits {
  updateEpisode: [episode: number]
}

defineProps<SeriesVideoPlayerProps>()

const emit = defineEmits<SeriesVideoPlayerEmits>()

const onUpdateEpisode = (episode: number): void => {
  emit('updateEpisode', episode)
}
</script>

<style lang="scss">
@use './styles/SeriesVideoPlayer.styles' as *;
</style>
