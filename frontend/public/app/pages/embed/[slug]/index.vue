<template>
  <template v-if="media && playback">
    <SeriesVideoPlayer
      v-if="season && episode"
      watermark
      :season
      :media
      :episode
      :playback
      watermark-link=""
      class="embed-player"
      @update-episode="onUpdateEpisode"
    />
    <MovieVideoPlayer v-else watermark :media :playback watermark-link="" class="embed-player" />
  </template>
</template>

<script lang="ts" setup>
import SeriesVideoPlayer from '~/components/features/player/SeriesVideoPlayer.vue'
import MovieVideoPlayer from '~/components/features/player/MovieVideoPlayer.vue'
import { useMediaDetail } from '~/composables/useMediaDetail'
import type { EpisodeModel } from '#shared/types/media'
import { useSlugParser } from '~/composables/useSlugParser'
import { useRoute } from '#imports'
import { toNumber } from '#shared/utils/normalize.utils'

definePageMeta({
  layout: false
})

const route = useRoute()

const { id } = useSlugParser(String(route.params.slug), 'Media')
const seasonNumber = computed<number | undefined>(() => toNumber(route.params.season))
const episodeNumber = computed<number | undefined>(() => toNumber(route.params.episode))

const { data: media, error: mediaError } = await useMediaDetail(id)

if (mediaError.value || !media.value) {
  throw createError({ statusCode: 404, message: 'Media not found.' })
}

if (isSeries(media.value.type) && (!seasonNumber.value || !episodeNumber.value)) {
  throw createError({ statusCode: 404, message: 'Media not found.' })
}

const { data: season, error: seasonError } = await useMediaSeason(id, seasonNumber)

if (seasonError.value) {
  throw createError({ statusCode: 404, message: 'Media not found.' })
}

const currentEpisodeNumber = ref<number | undefined>(episodeNumber.value)
const episode = computed<EpisodeModel | undefined>(() =>
  season.value?.episodes.find((episode) => episode.number === currentEpisodeNumber.value)
)
const playbackId = computed<number | null>(() => {
  if (episode.value) {
    return episode.value?.playbackId || null
  }

  return media.value?.playbackId || null
})

const { data: playback, error: playbackError } = await useMediaPlayback(playbackId)

if (playbackError.value || !playback.value) {
  throw createError({ statusCode: 404, message: 'Media not found.' })
}

const onUpdateEpisode = (episode: number): void => {
  currentEpisodeNumber.value = episode
}
</script>

<style lang="scss">
.embed-player {
  position: absolute;
  width: 100%;
  height: 100%;
}
</style>
