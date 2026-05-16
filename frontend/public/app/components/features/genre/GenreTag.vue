<template>
  <ABadge :as="NuxtLink" :to="link" :label="genre.name" class="genre-tag">
    {{ genre.name }}
  </ABadge>
</template>

<script setup lang="ts">
import ABadge from '~/components/ui/badge/ABadge.vue'
import { NuxtLink } from '#components'
import type { GenreShortModel } from '#shared/types/genre'

export interface AGenreTagProps {
  genre: GenreShortModel
}

const props = defineProps<AGenreTagProps>()
const link = computed<string>(() => `/filters/genre/${props.genre.id}-${props.genre.slug}`)
</script>

<style lang="scss">
@use './styles/GenreTag.styles' as *;
</style>
