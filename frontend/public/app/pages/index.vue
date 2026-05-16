<template>
  <!-- Block with service benefits -->
  <AContainer class="a-section-mt--sm">
    <AppPlatformBenefits />
  </AContainer>

  <!-- Hero block displaying new, popular, or other media -->
  <AContainer v-if="featuredMedia" class="a-section-mt--sm">
    <HomeFeaturedMedia :media="featuredMedia" />
  </AContainer>

  <!-- Block displaying media collections -->
  <AContainer
    v-for="(collection, i) in collections || []"
    :key="collection.id"
    class="a-section-mt--md"
  >
    <MediaCollection
      :collection
      :media-limit="COLLECTION_MEDIA_LIMIT"
      :lazy="i > MAX_PRELOAD_COLLECTIONS"
    />
  </AContainer>

  <!-- Block displaying popular genres -->
  <AContainer v-if="featuredGenres" class="a-section-mt--md">
    <AContentSection title="Жанры" :append-link="{ to: '/genres', label: 'Смотреть все' }">
      <GenreCardsList :genres="featuredGenres" />
    </AContentSection>
  </AContainer>
</template>

<script lang="ts" setup>
import AContentSection from '~/components/ui/section/AContentSection.vue'
import GenreCardsList from '~/components/features/genre/GenreCardsList.vue'
import AContainer from '~/components/ui/container/AContainer.vue'
import AppPlatformBenefits from '~/components/app/platform-benefits/AppPlatformBenefits.vue'
import HomeFeaturedMedia from '~/components/features/media/HomeFeaturedMedia.vue'
import MediaCollection from '~/components/features/collection/MediaCollection.vue'
import { useFeaturedMedia } from '~/composables/useFeaturedMedia'
import { useHomePageCollections } from '~/composables/useHomePageCollections'
import { useFeaturedGenres } from '~/composables/useFeaturedGenres'
import { useHomeSeo } from '~/composables/useHomeSeo'
import { useCollectionMediaList } from '~/composables/useCollectionMediaList'

// The maximum number of collections for which media will be loaded on the server side
const MAX_PRELOAD_COLLECTIONS = 5

// Limit of displayed media cards in a collection
const COLLECTION_MEDIA_LIMIT = 10

const { data: featuredMedia } = await useFeaturedMedia()
const { data: collections } = await useHomePageCollections()
const { data: featuredGenres } = await useFeaturedGenres(12)

const seoCollections = collections.value?.slice(0, MAX_PRELOAD_COLLECTIONS) || []
const seoCollectionMedia = await Promise.all(
  seoCollections.map(async (collection) => {
    const { data } = await useCollectionMediaList(collection.id, 1, COLLECTION_MEDIA_LIMIT, true)

    return {
      entity: collection,
      media: data.value?.data.slice(0, 6) ?? []
    }
  })
)

useHomeSeo({
  featuredMedia,
  collections: seoCollectionMedia
})
</script>

<style lang="scss">
@use '~/assets/scss/pages/home';
</style>
