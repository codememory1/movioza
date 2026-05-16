<template>
  <ul class="genre-cards-list">
    <slot name="items" :variant="variant" :genres="genres">
      <GenreCard v-for="genre in genres" :key="genre.id" :variant :genre />
    </slot>
  </ul>
</template>

<script lang="ts" setup>
import GenreCard from '~/components/features/genre/GenreCard.vue'
import type { GenreCardType } from '~/components/features/genre/types/GenreCard.types'
import type { GenreModel } from '#shared/types/genre'

export interface AGenresListProps {
  variant?: GenreCardType
  genres: GenreModel[]
}

defineProps<AGenresListProps>()
</script>

<style lang="scss">
@use './styles/GenreCardsList.styles' as *;
</style>
