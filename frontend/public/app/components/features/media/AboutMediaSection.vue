<template>
  <AContentSection
    :title="getAboutMediaLabel(media.type, media.kind)"
    class="about-media-section"
    content-class="about-media-section__content"
  >
    <div class="about-media-section__meta">
      <p class="about-media-section__description">{{ media.description }}</p>
      <table class="about-media-section__meta-table">
        <tbody>
          <tr class="about-media-section__meta-table-row">
            <th>Другие названия</th>
            <td>{{ media.alternativeTitles.join(', ') }}</td>
          </tr>
          <tr class="about-media-section__meta-table-row">
            <th>Страны</th>
            <td class="badges-block">
              {{ media.country }}
            </td>
          </tr>
          <tr class="about-media-section__meta-table-row">
            <th>Жанры</th>
            <td class="badges-block">
              <GenreTag v-for="genre in media.genres" :key="genre.id" :genre />
            </td>
          </tr>
          <tr v-if="seasonsText && episodesText" class="about-media-section__meta-table-row">
            <th>Длительность</th>
            <td>{{ seasonsText }} | {{ episodesText }}</td>
          </tr>
          <tr class="about-media-section__meta-table-row">
            <th>Время</th>
            <td>{{ formatDuration(media.duration) }}</td>
          </tr>
          <tr class="about-media-section__meta-table-row">
            <th>Год</th>
            <td>{{ media.releasedAt.getFullYear() }}</td>
          </tr>
          <tr class="about-media-section__meta-table-row">
            <th>Ограничения</th>
            <td>{{ media.ageRestrictions }}+</td>
          </tr>
        </tbody>
      </table>
    </div>
    <RatingPanel
      class="about-media-section__rating"
      :rating="media.rating"
      :votes="media.votes"
      @select-rating="emit('selectRating', $event)"
    />
  </AContentSection>
</template>

<script lang="ts" setup>
import { getAboutMediaLabel } from '#shared/utils/content.utils'
import AContentSection from '~/components/ui/section/AContentSection.vue'
import RatingPanel from '~/components/features/rating/RatingPanel.vue'
import GenreTag from '~/components/features/genre/GenreTag.vue'
import type { MediaDetailModel } from '#shared/types/media'

export interface AboutMediaSectionProps {
  media: MediaDetailModel
}

export interface AboutMediaSectionEmits {
  selectRating: [rating: number]
}

const props = defineProps<AboutMediaSectionProps>()
const emit = defineEmits<AboutMediaSectionEmits>()
const seasonsText = computed<string | null>(() => {
  const count = props.media.seasonCount

  if (!count) {
    return null
  }

  return `${count} ${plural(count, ['сезон', 'сезона', 'сезонов'])}`
})
const episodesText = computed<string | null>(() => {
  const count = props.media.episodeCount

  if (!count) {
    return null
  }

  return `${count} ${plural(count, ['эпизод', 'эпизода', 'эпизодов'])}`
})
</script>

<style lang="scss">
@use './styles/AboutMediaSection.styles' as *;
</style>
