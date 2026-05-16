<template>
  <AContentSection title="Сезоны" class="media-seasons-section">
    <template #appendHeader>
      <NuxtLink
        v-for="number in media.seasonCount"
        :key="number"
        :to="`/watch/${media.id}-${media.slug}/sezon-${number}`"
        class="media-seasons-section__season-btn"
        :class="{
          selected: number === season.number
        }"
      >
        {{ number }}
      </NuxtLink>
    </template>
    <ASlider :slides="season.episodes" id-key="number" :per-view="4">
      <template #default="{ slide }">
        <EpisodeCard :media :season :episode="slide" :selected="slide.number === selectedEpisode" />
      </template>
    </ASlider>
  </AContentSection>
</template>

<script lang="ts" setup>
import AContentSection from '~/components/ui/section/AContentSection.vue'
import ASlider from '~/components/ui/slider/ASlider.vue'
import EpisodeCard from '~/components/features/episode/EpisodeCard.vue'
import type { MediaDetailModel, SeasonModel } from '#shared/types/media'

export interface MediaSeasonsSectionProps {
  media: MediaDetailModel
  season: SeasonModel
  selectedEpisode: number
}

defineProps<MediaSeasonsSectionProps>()
</script>

<style lang="scss">
@use './styles/MediaSeasonsSection.styles' as *;
</style>
