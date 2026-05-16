<template>
  <div class="rating-panel">
    <div class="rating-panel__container">
      <div class="rating-panel__rating-block">
        <span
          class="rating-panel__rating-value"
          :class="{
            bad: rating < 5,
            good: rating >= 5 && rating < 8,
            excellent: rating >= 8
          }"
        >
          {{ rating.toFixed(1) }}
        </span>
      </div>
      <div class="rating-panel__text-block">
        <span class="rating-panel__title">Пользовательский рейтинг</span>
        <span class="rating-panel__extra">{{ formatNumber(votes) }} голосов</span>
      </div>
    </div>
    <div class="rating-panel__container">
      <AStarRating v-model="selectedRating" @update:model-value="emit('selectRating', $event)" />
    </div>
  </div>
</template>

<script lang="ts" setup>
import AStarRating from '~/components/ui/rating/AStarRating.vue'
import { formatNumber } from '~/utils/format'

export interface RatingPanelProps {
  rating: number
  votes: number
}

export interface RatingPanelEmits {
  selectRating: [rating: number]
}

defineProps<RatingPanelProps>()

const emit = defineEmits<RatingPanelEmits>()
const selectedRating = ref<number>(0)
</script>

<style lang="scss">
@use './styles/RatingPanel.styles' as *;
</style>
