<template>
  <ul class="collection-cards-list">
    <CollectionCard v-for="collection in collections" :key="collection.id" :collection />
  </ul>
</template>

<script lang="ts" setup>
import CollectionCard from './CollectionCard.vue'
import type { CollectionListItemModel } from '#shared/types/collection'

export interface CollectionCardsListProps {
  collections: CollectionListItemModel[]
}

defineProps<CollectionCardsListProps>()
</script>

<style lang="scss">
@use './styles/CollectionCardsList.styles' as *;
</style>
