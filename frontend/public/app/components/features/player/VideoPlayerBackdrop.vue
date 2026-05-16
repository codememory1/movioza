<template>
  <AImage
    :src="backdrop.url"
    :alt="`Фоновое изображение «${title}»`"
    :width="backdrop.width"
    :height="backdrop.height"
    :blur-hash="backdrop.blurHash"
    :aria-label="`Фоновое изображение «${title}»`"
    :preserve-aspect-ratio="false"
    v-slot="{ onLoad, onError }"
    class="video-player-backdrop"
  >
    <picture>
      <source
        v-for="(source, i) in sources"
        :key="i"
        :media="source.media"
        :srcset="source.srcset"
      />
      <NuxtImg
        :src="backdrop.url"
        :alt="`Фоновое изображение «${title}»`"
        loading="eager"
        fetchpriority="high"
        decoding="async"
        @load="onLoad"
        @error="onError"
      />
    </picture>
  </AImage>
</template>

<script lang="ts" setup>
import type { MediaSourceModel } from '#shared/types/media-source'
import type { ImageModel } from '#shared/types/common'
import AImage from '~/components/ui/image/AImage.vue'

export interface VideoPlayerPosterProps {
  title: string
  sources: MediaSourceModel[]
  backdrop: ImageModel
}

defineProps<VideoPlayerPosterProps>()
</script>

<style lang="scss">
@use 'styles/VideoPlayerBackdrop.styles' as *;
</style>
