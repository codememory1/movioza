<template>
  <div class="collection-page">
    <AContainer class="a-section-mt--sm">
      <MediaCardsList v-if="mediaList" :items="mediaList.data" />
    </AContainer>
    <APagination
      v-if="mediaList"
      v-model="page"
      :total="mediaList.meta.pagination.totalPages"
      :link="(number: number) => AppRoutes.collectionPage(id, slug, number)"
      class="collection-page__pagination"
    />
  </div>
</template>

<script lang="ts" setup>
import AContainer from '~/components/ui/container/AContainer.vue'
import MediaCardsList from '~/components/features/media/MediaCardsList.vue'
import { useSlugParser } from '~/composables/useSlugParser'
import { definePageMeta, useCollectionDetail, useCollectionMediaList, useRoute } from '#imports'
import APagination from '~/components/ui/pagination/APagination.vue'
import { AppRoutes } from '#shared/router/routes'
import { useCollectionDetailSeo } from '~/composables/useCollectionDetailSeo'

definePageMeta({
  middleware: ['collection-middleware']
})

const route = useRoute()

const page = ref<number>(Number(route.params.page || 1))
const { id, slug } = useSlugParser(String(route.params.slug), 'Collection')
const { data: collection } = await useCollectionDetail(id)
const { data: mediaList } = await useCollectionMediaList(id, page, 20, true)

if (!mediaList.value) {
  throw createError({ status: 404, message: 'Collection not found' })
}

useCollectionDetailSeo({
  collection,
  page,
  totalPages: computed(() => mediaList.value?.meta.pagination.totalPages || 1)
})
</script>

<style lang="scss">
@use '~/assets/scss/pages/collection' as *;
</style>
