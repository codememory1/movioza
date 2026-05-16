<template>
  <div class="media-cards-list">
    <MediaCard v-for="item in items" :key="item.id" :media="item" />
  </div>
</template>

<script lang="ts" setup>
import type { MediaShortModel } from '#shared/types/media'
import MediaCard from '~/components/features/media/MediaCard.vue'

export interface MediaCardsListProps {
  items: MediaShortModel[]
}

defineProps<MediaCardsListProps>()
</script>

<style lang="scss">
@use './styles/MediaCardsList.styles' as *;
</style>
