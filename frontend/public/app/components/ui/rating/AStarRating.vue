<template>
  <ARating v-model="model" :labels="labels" class="a-star-rating">
    <template #item="{ num }">
      <AStarOutlineIcon aria-hidden="true" />
      <span class="a-star-rating__num" aria-hidden="true">{{ num }}</span>
    </template>
  </ARating>
</template>

<script lang="ts" setup>
import AStarOutlineIcon from '~/components/ui/icon/AStarOutlineIcon.vue'
import ARating from '~/components/ui/rating/ARating.vue'

const model = defineModel<number>({ required: true })
const labels = computed<string[]>(() => [
  '1 звезда',
  '2 звезды',
  '3 звезды',
  '4 звезды',
  '5 звезд',
  '6 звезд',
  '7 звезд',
  '8 звезд',
  '9 звезд',
  '10 звезд'
])
</script>

<style lang="scss">
@use './styles/AStarRating.styles' as *;
</style>
