<template>
  <NuxtLink :to="link" class="episode-card" :class="{ 'episode-card--selected': selected }">
    <div class="episode-card__container">
      <AImage
        :src="preview.url"
        :alt="`${media.title} - ${episode.number} серия ${episode.title}`"
        :width="preview.width"
        :height="preview.height"
        :blur-hash="preview.blurHash"
        class="episode-card__img"
        v-slot="{ src, onLoad, onError }"
      >
        <picture>
          <source
            v-for="(previewSource, i) in episode.previewSources"
            :key="i"
            :media="previewSource.media"
            :srcset="previewSource.srcset"
          />
          <NuxtImg
            :src
            :alt="`${media.title} - ${episode.number} серия ${episode.title}`"
            loading="lazy"
            @load="onLoad"
            @error="onError"
          />
        </picture>
      </AImage>
      <div class="episode-card__info">
        <span class="episode-card__title">
          <APlayRateIcon v-if="selected" /> {{ episode.number }}.
          {{ episode.title }}
        </span>
        <p class="episode-card__description">
          {{ episode.description }}
        </p>
        <span class="episode-card__duration">{{ formatDuration(episode.duration) }}</span>
      </div>
    </div>
  </NuxtLink>
</template>

<script lang="ts" setup>
import APlayRateIcon from '~/components/ui/icon/APlayRateIcon.vue'
import type { EpisodeModel, MediaShortModel, SeasonShortModel } from '#shared/types/media'
import AImage from '~/components/ui/image/AImage.vue'
import type { ImageModel } from '#shared/types/common'

export interface EpisodeCardProps {
  media: MediaShortModel
  season: SeasonShortModel
  episode: EpisodeModel
  selected?: boolean
}

const props = withDefaults(defineProps<EpisodeCardProps>(), {
  selected: false
})
const link = computed<string>(
  () => `${props.media.watchPath}/${props.season.watchSegment}/${props.episode.watchSegment}`
)
const preview = computed<ImageModel>(() => props.episode.preview ?? props.media.poster)
</script>

<style lang="scss">
@use './styles/EpisodeCard.styles' as *;
</style>
