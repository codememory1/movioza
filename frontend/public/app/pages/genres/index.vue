<template>
  <AContainer v-if="allGenres" class="a-section-mt--sm">
    <GenreCardsList :genres="allGenres" />
  </AContainer>
  <AContainer v-if="featuredGenres" class="a-section-mt--md">
    <AContentSection title="Популярные жанры">
      <GenreCardsList :genres="featuredGenres" />
    </AContentSection>
  </AContainer>
</template>

<script lang="ts" setup>
import GenreCardsList from '~/components/features/genre/GenreCardsList.vue'
import { useGenres } from '~/composables/useGenres'
import AContainer from '~/components/ui/container/AContainer.vue'
import { useFeaturedGenres } from '~/composables/useFeaturedGenres'
import AContentSection from '~/components/ui/section/AContentSection.vue'

const { data: allGenres } = await useGenres()
const { data: featuredGenres } = await useFeaturedGenres(12)

definePageMeta({
  pageTitle: 'Все жанры',
  pageDescription: 'Выберите жанр и откройте для себя тысячи фильмов и сериалов'
})
</script>

<style lang="scss">
@use '~/assets/scss/pages/genres';
</style>
