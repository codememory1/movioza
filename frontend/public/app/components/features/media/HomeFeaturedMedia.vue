<template>
  <section class="home-featured-media">
    <div class="home-featured-media__container">
      <h1 class="visually-hidden">Смотреть фильмы онлайн бесплатно в хорошем качестве</h1>
      <div class="home-featured-media__bg" aria-hidden="true">
        <AImage
          preload
          :src="media.poster.url"
          alt=""
          :width="media.poster.width"
          :height="media.poster.height"
          :blur-hash="media.poster.blurHash"
          fetchpriority="high"
        />
      </div>
      <div class="home-featured-media__content">
        <article class="home-featured-media__article">
          <h2 class="home-featured-media__title">{{ media.title }}</h2>
          <ul class="home-featured-media__meta">
            <li class="home-featured-media__meta-item rating">
              <AStarRateIcon />
              {{ media.rating.toFixed(1) }}
            </li>
            <li class="home-featured-media__meta-item release-year">
              <MediaYearTag :year="media.releasedAt.getFullYear()" />
            </li>
            <li class="home-featured-media__meta-item duration">
              <AClockOutlineIcon />
              {{ formatDuration(media.duration) }}
            </li>
            <li class="home-featured-media__meta-item quality">
              {{ media.quality }}
            </li>
          </ul>
          <ul class="home-featured-media__genres">
            <li v-for="genre in media.genres" :key="genre.id">
              <GenreTag :genre />
            </li>
          </ul>
          <p class="home-featured-media__description">
            {{ media.description }}
          </p>
          <div class="home-featured-media__actions">
            <NuxtLink :to="media.watchPath" class="home-featured-media__action-btn watch">
              <APlayRateIcon />
              Смотреть бесплатно
            </NuxtLink>
            <NuxtLink to="" class="home-featured-media__action-btn add-to-favorites">
              <ABookMarkOutlineIcon />
              В избранное
            </NuxtLink>
          </div>
        </article>
        <div class="home-featured-media__poster">
          <AImage
            preload
            :src="media.poster.url"
            :alt="`${media.title} — постер`"
            :width="media.poster.width"
            :height="media.poster.height"
            :blur-hash="media.poster.blurHash"
            fetchpriority="high"
            class="home-featured-media__poster-img"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script lang="ts" setup>
import AClockOutlineIcon from '~/components/ui/icon/AClockOutlineIcon.vue'
import AStarRateIcon from '~/components/ui/icon/AStarRateIcon.vue'
import GenreTag from '~/components/features/genre/GenreTag.vue'
import ABookMarkOutlineIcon from '~/components/ui/icon/ABookMarkOutlineIcon.vue'
import APlayRateIcon from '~/components/ui/icon/APlayRateIcon.vue'
import MediaYearTag from '~/components/features/media/MediaYearTag.vue'
import { formatDuration } from '~/utils/format'
import type { MediaShortModel } from '#shared/types/media'
import AImage from '~/components/ui/image/AImage.vue'

export interface HomeFeaturedMediaProps {
  media: MediaShortModel
}

defineProps<HomeFeaturedMediaProps>()
</script>

<style lang="scss">
@use './styles/HomeFeaturedMedia.styles' as *;
</style>
