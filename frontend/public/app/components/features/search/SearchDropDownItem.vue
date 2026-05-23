<template>
  <NuxtLink v-if="media" :to="AppRoutes.watchModel(media)" class="search-drop-down-item">
    <div class="search-drop-down-item__inner">
      <AImage
        :src="media.poster.url"
        :alt="media.title"
        :width="media.poster.width"
        :height="media.poster.height"
        :blur-hash="media.poster.blurHash"
        class="search-drop-down-item__poster"
      />

      <div class="search-drop-down-item__content">
        <h3 class="search-drop-down-item__title">{{ media.title }}</h3>
        <div class="search-drop-down-item__meta">
          <span class="search-drop-down-item__meta-item search-drop-down-item__rating">
            <AStarRateIcon aria-hidden="true" /> {{ media.rating }}
          </span>
          <span class="search-drop-down-item__meta-item search-drop-down-item__release">
            {{ media.releasedAt.getFullYear() }}
          </span>
          <span class="search-drop-down-item__meta-item search-drop-down-item__duration">
            {{ formatDuration(media.duration) }}
          </span>
        </div>
        <div class="search-drop-down-item__genres">
          <GenreTag v-for="genre in media.genres" :key="genre.id" :genre />
        </div>
        <p class="search-drop-down-item__description">{{ media.description }}</p>
      </div>
    </div>
  </NuxtLink>
  <div v-else class="search-drop-down-item search-drop-down-item--skeleton a-skeleton" />
</template>

<script lang="ts" setup>
import type { MediaShortModel } from '#shared/types/media'
import AImage from '~/components/ui/image/AImage.vue'
import GenreTag from '~/components/features/genre/GenreTag.vue'
import AStarRateIcon from '~/components/ui/icon/AStarRateIcon.vue'
import { formatDuration } from '~/utils/format'
import { AppRoutes } from '#shared/router/routes'

export interface SearchDropDownItemProps {
  media?: MediaShortModel
}

defineProps<SearchDropDownItemProps>()
</script>

<style lang="scss">
@use './styles/SearchDropDownItem.styles' as *;
</style>
