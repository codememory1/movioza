<template>
  <article class="media-hero-content">
    <div class="media-hero-content__container">
      <div class="media-hero-content__title">
        <NuxtImg
          class="media-hero-content__title-img"
          :src="media.logo"
          :alt="media.title"
          draggable="false"
        />
        <h1 class="media-hero-content__header visually-hidden">
          {{ media.title }}
        </h1>
      </div>
      <div class="media-hero-content__meta">
        <div class="media-hero-content__meta-item rating">
          <AStarRateIcon /> {{ media.rating.toFixed(1) }}
        </div>
        <div class="media-hero-content__meta-item year">
          <MediaYearTag :year="media.releasedAt.getFullYear()" />
        </div>
        <div class="media-hero-content__meta-item duration">
          {{ formatDuration(media.duration) }}
        </div>
      </div>
      <div class="media-hero-content__genres">
        <GenreTag
          v-for="genre in media.genres"
          :key="genre.slug"
          :genre
          class="media-hero-content__genre"
        />
      </div>
      <p class="media-hero-content__description">{{ media.description }}</p>
      <dl class="media-hero-content__credits">
        <div class="media-hero-content__credits-item">
          <dt class="media-hero-content__credits-label">Режиссёр:</dt>
          <dd class="media-hero-content__credits-value">
            <DirectorTag
              v-for="director in media.directors"
              :key="director.slug"
              :director
              class="a-link"
            />
          </dd>
        </div>
        <div class="media-hero-content__credits-item">
          <dt class="media-hero-content__credits-label">В ролях:</dt>
          <dd class="media-hero-content__credits-value">
            <ActorTag v-for="actor in media.actors" :key="actor.slug" :actor class="a-link" />
          </dd>
        </div>
      </dl>
      <div class="media-hero-content__actions">
        <button class="media-hero-content__play-btn" aria-label="Смотреть">
          <APlayOutlineIcon /> Смотреть
        </button>
        <button class="media-hero-content__like-btn" aria-pressed="false" aria-label="Понравилось">
          <AThumbsUpOutlineIcon />
        </button>
        <button
          class="media-hero-content__favorites-btn"
          aria-pressed="false"
          aria-label="Добавить в избранное"
        >
          <ABookMarkOutlineIcon />
        </button>
        <button class="media-hero-content__share-btn" aria-label="Поделиться">
          <AShareOutlineIcon />
        </button>
      </div>
    </div>
  </article>
</template>

<script lang="ts" setup>
import MediaYearTag from '~/components/features/media/MediaYearTag.vue'
import GenreTag from '~/components/features/genre/GenreTag.vue'
import APlayOutlineIcon from '~/components/ui/icon/APlayOutlineIcon.vue'
import ABookMarkOutlineIcon from '~/components/ui/icon/ABookMarkOutlineIcon.vue'
import AThumbsUpOutlineIcon from '~/components/ui/icon/AThumbsUpOutlineIcon.vue'
import AShareOutlineIcon from '~/components/ui/icon/AShareOutlineIcon.vue'
import AStarRateIcon from '~/components/ui/icon/AStarRateIcon.vue'
import DirectorTag from '~/components/features/person/DirectorTag.vue'
import ActorTag from '~/components/features/person/ActorTag.vue'
import type { MediaDetailModel } from '#shared/types/media'

export interface MediaHeroContentProps {
  media: MediaDetailModel
}

defineProps<MediaHeroContentProps>()
</script>

<style lang="scss">
@use './styles/MediaHeroContent.styles' as *;
</style>
