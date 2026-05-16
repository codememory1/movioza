<template>
  <AContentSection ref="sectionRef" :title="collection.title">
    <ASlider v-if="isSkeleton" :slides="createSkeletonSlides(mediaLimit)" id-key="id">
      <MediaCard />
    </ASlider>
    <ASlider v-else :slides="mediaList!.data" id-key="id" v-slot="{ slide }">
      <MediaCard :media="slide" />
    </ASlider>
  </AContentSection>
</template>

<script lang="ts" setup>
import ASlider from '~/components/ui/slider/ASlider.vue'
import MediaCard from '~/components/features/media/MediaCard.vue'
import AContentSection from '~/components/ui/section/AContentSection.vue'
import type { CollectionShortModel } from '#shared/types/collection/collection.model'
import { useCollectionMediaList } from '~/composables/useCollectionMediaList'
import { useIntersectionObserver } from '@vueuse/core'
import type { MediaShortModel } from '#shared/types/media'

export interface MediaCollectionProps {
  collection: CollectionShortModel
  mediaLimit: number
  lazy?: boolean
}

export interface MediaCollectionEmits {
  loaded: [items: MediaShortModel[]]
}

const props = withDefaults(defineProps<MediaCollectionProps>(), {
  lazy: false
})
const emit = defineEmits<MediaCollectionEmits>()
const { data: mediaList, execute } = await useCollectionMediaList(
  props.collection.id,
  1,
  props.mediaLimit,
  !props.lazy
)
const sectionRef = ref<InstanceType<typeof AContentSection>>()
const isSkeleton = computed<boolean>(() => !mediaList.value)

if (props.lazy) {
  const { stop: stopObserver } = useIntersectionObserver(sectionRef, async ([entry]) => {
    const visible = entry?.isIntersecting || false

    if (!visible) {
      return
    }

    stopObserver()

    await execute()
  })
}

const createSkeletonSlides = (count: number) => {
  return Array.from({ length: count }, (_, i) => ({
    id: `skeleton-${i}`
  }))
}

watch(
  mediaList,
  () => {
    if (!mediaList.value) {
      return
    }

    emit('loaded', mediaList.value.data)
  },
  { immediate: true }
)
</script>
