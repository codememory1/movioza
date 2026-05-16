<template>
  <article class="media-card" :class="{ 'media-card--skeleton': !media }">
    <template v-if="media">
      <div class="media-card__hover">
        <div class="media-card__play-icon">
          <APlayRateIcon />
        </div>
      </div>
      <div class="media-card__quality-wrapper">
        <span class="media-card__quality">{{ media.quality }}</span>
      </div>
      <NuxtLink
        :to="media.watchPath"
        :title="`Смотреть: ${media.title}`"
        :aria-label="`Смотреть: ${media.title}`"
        class="media-card__poster-link"
      >
        <AImage
          :src="media.poster.url"
          :alt="media.title"
          :width="media.poster.width"
          :height="media.poster.height"
          :blur-hash="media.poster.blurHash"
          class="media-card__poster-img"
        />
      </NuxtLink>
      <div class="media-card__info">
        <h3 class="media-card__title">
          <NuxtLink :to="media.watchPath" :title="media.title">{{ media.title }}</NuxtLink>
        </h3>
        <ul class="media-card__meta">
          <li class="media-card__meta-item">
            <ACalendarOutlineIcon />
            <MediaYearTag :year="media.releasedAt.getFullYear()" />
          </li>
          <li class="media-card__meta-item rating">
            <AStarRateIcon />
            {{ media.rating }}
          </li>
          <li class="media-card__meta-item">
            <AClockOutlineIcon />
            {{ formatDuration(media.duration) }}
          </li>
        </ul>
        <ul class="media-card__genres">
          <li v-for="genre in media.genres" :key="genre.slug">
            <GenreTag :genre />
          </li>
        </ul>
      </div>
    </template>
  </article>
</template>

<script lang="ts" setup>
import ACalendarOutlineIcon from '~/components/ui/icon/ACalendarOutlineIcon.vue'
import AClockOutlineIcon from '~/components/ui/icon/AClockOutlineIcon.vue'
import AStarRateIcon from '~/components/ui/icon/AStarRateIcon.vue'
import GenreTag from '~/components/features/genre/GenreTag.vue'
import MediaYearTag from '~/components/features/media/MediaYearTag.vue'
import APlayRateIcon from '~/components/ui/icon/APlayRateIcon.vue'
import { formatDuration } from '~/utils/format'
import type { MediaShortModel } from '#shared/types/media'
import AImage from '~/components/ui/image/AImage.vue'

export interface AMediaCardProps {
  media?: MediaShortModel
}

defineProps<AMediaCardProps>()
</script>

<style lang="scss">
@use './styles/MediaCard.styles' as *;
</style>
