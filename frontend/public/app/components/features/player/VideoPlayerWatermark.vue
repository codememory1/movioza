<template>
  <a
    class="video-player-watermark"
    :class="{ 'video-player-watermark--raised': raised }"
    :href="link"
    target="_blank"
    :aria-label="title"
    :title="title"
  >
    <img src="/images/logo.png" :alt="appTitle" />
  </a>
</template>

<script lang="ts" setup>
export interface VideoPlayerWatermarkProps {
  link?: string
  raised?: boolean
}

const props = defineProps<VideoPlayerWatermarkProps>()
const config = useRuntimeConfig()
const link = computed<string>(() => props.link || config.public.appUrl)
const appTitle = computed<string>(() => config.public.appShortTitle)
const title = computed<string>(
  () => `Вернуться на ${appTitle.value} для поиска других фильмов и сериалов`
)
</script>

<style lang="scss">
@use './styles/VideoPlayerWatermark.styles' as *;
</style>
