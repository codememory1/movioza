<template>
  <li
    class="genre-card"
    :class="`genre-card--${variant}`"
    :style="{
      '--genre-card-from': gradient.from,
      '--genre-card-to': gradient.to
    }"
  >
    <NuxtLink :to="link" class="genre-card__wrapper" :title="genre.name">
      <span class="genre-card__icon" aria-hidden="true">{{ genre.emoji }}</span>
      <span class="genre-card__content">
        <span class="genre-card__title">{{ genre.name }}</span>
        <span v-if="genre.subtitle" class="genre-card__subtitle">{{ genre.subtitle }}</span>
      </span>
      <AArrowRightIcon v-if="variant === 'horizontal'" class="genre-card__arrow-icon" />
    </NuxtLink>
  </li>
</template>

<script lang="ts" setup>
import AArrowRightIcon from '~/components/ui/icon/AArrowRightIcon.vue'
import type { GenreModel } from '#shared/types/genre'
import type { GenreCardType } from '~/components/features/genre/types/GenreCard.types'

export interface GenreCardProps {
  variant?: GenreCardType
  genre: GenreModel
}

const props = withDefaults(defineProps<GenreCardProps>(), {
  variant: 'tile'
})
const gradient = computed(() => generateGradientFromSlug(props.genre.slug))
const link = computed<string>(() => `/filters/genre/${props.genre.id}-${props.genre.slug}`)
</script>

<style lang="scss">
@use 'styles/GenreCard.styles' as *;
</style>
