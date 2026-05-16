<template>
  <div
    ref="containerRef"
    class="a-image"
    :class="{ 'a-image--loaded': isLoaded }"
    :style="{
      aspectRatio: preserveAspectRatio ? width / height : undefined,
      backgroundColor: dominantColor
    }"
  >
    <canvas v-if="!isLoaded" ref="canvasRef" class="a-image__canvas" aria-hidden="true" />

    <slot :on-load="onLoad" :on-error="onError" :is-loaded="isLoaded" :src="src">
      <NuxtImg
        :src
        :alt
        :width
        :height
        :loading
        :sizes
        :preload
        :fetchPriority
        class="a-image__img"
        @load="onLoad"
        @error="onError"
      />
    </slot>
  </div>
</template>

<script lang="ts" setup>
import { decodeBlurHash } from 'fast-blurhash'

export interface AImageProps {
  src: string
  alt: string
  width: number
  height: number
  blurHash: string
  loading?: 'lazy' | 'eager'
  sizes?: string
  dominantColor?: string
  preserveAspectRatio?: boolean
  preload?: boolean
  fetchPriority?: 'high'
}

const props = withDefaults(defineProps<AImageProps>(), {
  loading: 'lazy',
  dominantColor: 'var(--a-secondary)',
  preserveAspectRatio: true
})
const containerRef = ref<HTMLElement>()
const canvasRef = ref<HTMLCanvasElement>()
const isLoaded = ref<boolean>(false)
const ratio = computed<number>(() => props.width / props.height)

onMounted(async () => {
  renderBlurHash()

  await markAsLoadedIfImageAlreadyReady()
})

watch(
  () => props.src,
  async () => {
    isLoaded.value = false

    await nextTick()

    renderBlurHash()

    await markAsLoadedIfImageAlreadyReady()
  }
)

const renderBlurHash = (): void => {
  if (!canvasRef.value) {
    return
  }

  const baseSize = 32
  const width = baseSize
  const height = Math.round(baseSize / ratio.value)
  const pixels = decodeBlurHash(props.blurHash, width, height)
  const ctx = canvasRef.value.getContext('2d')

  if (null === ctx) {
    return
  }

  canvasRef.value.width = width
  canvasRef.value.height = height

  const imageData = ctx.createImageData(width, height)

  imageData.data.set(pixels)

  ctx.putImageData(imageData, 0, 0)
}

const onLoad = (): void => {
  isLoaded.value = true
}

const onError = (): void => {
  isLoaded.value = true
}

const markAsLoadedIfImageAlreadyReady = async (): Promise<void> => {
  await nextTick()

  const img = containerRef.value?.querySelector('img')

  if (img?.complete && img.naturalWidth > 0) {
    onLoad()
  }
}
</script>

<style lang="scss">
@use './styles/AImage.styles' as *;
</style>
