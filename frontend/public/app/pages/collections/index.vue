<template>
  <div class="collections-page">
    <AContainer v-if="collectionsList" class="a-section-mt--sm">
      <CollectionCardsList :collections="collectionsList.data" />
    </AContainer>

    <APagination
      v-model="page"
      :total="collectionsList?.meta.pagination.totalPages || 0"
      :link="(number: number) => AppRoutes.collectionsPage(number)"
      class="collections-page__pagination"
    />
  </div>
</template>

<script lang="ts" setup>
import AContainer from '~/components/ui/container/AContainer.vue'
import CollectionCardsList from '~/components/features/collection/CollectionCardsList.vue'
import { useCollectionsList } from '~/composables/useCollectionsList'
import APagination from '~/components/ui/pagination/APagination.vue'
import { AppRoutes } from '#shared/router/routes'
import { useCollectionsSeo } from '~/composables/useCollectionsSeo'

definePageMeta({
  pageTitle: 'Все коллекции',
  pageDescription: 'Открывайте коллекции фильмов и сериалов по жанрам, настроению и популярности.'
})

const COLLECTIONS_LIMIT = 25

const route = useRoute()
const page = ref<number>(Number(route.params.page || 1))

const { data: collectionsList } = await useCollectionsList(page, COLLECTIONS_LIMIT)

useCollectionsSeo({
  page,
  totalPages: computed(() => collectionsList.value?.meta.pagination.totalPages || 1)
})
</script>

<style lang="scss">
@use '~/assets/scss/pages/collections' as *;
</style>
