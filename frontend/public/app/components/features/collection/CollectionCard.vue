<template>
  <li
    class="collection-card"
    :style="{
      '--collection-card-from': gradient.from,
      '--collection-card-to': gradient.to
    }"
    :aria-label="collection.title"
  >
    <NuxtLink class="collection-card__link" :to="AppRoutes.collectionModel(collection)">
      <div class="collection-card__top">
        <span class="collection-card__media-count">{{ collection.mediaCount }}</span>
        <AArrowRightIcon aria-hidden="true" class="collection-card__arrow-icon" />
      </div>
      <div class="collection-card__content">
        <span class="collection-card__title">{{ collection.title }}</span>
        <span class="collection-card__description">{{ collection.description }}</span>
      </div>
    </NuxtLink>
  </li>
</template>

<script lang="ts" setup>
import type { CollectionListItemModel } from '#shared/types/collection'
import AArrowRightIcon from '~/components/ui/icon/AArrowRightIcon.vue'
import { AppRoutes } from '#shared/router/routes'
import { generateGradientFromSlug } from '~/utils/generator'

export interface CollectionCardProps {
  collection: CollectionListItemModel
}

const props = defineProps<CollectionCardProps>()
const gradient = computed(() => generateGradientFromSlug(props.collection.slug))
</script>

<style lang="scss">
@use './styles/CollectionCard.styles' as *;
</style>
