<template>
  <NuxtLink class="media-year-tag" :to="`/filters/year/${year}`">
    {{ year }}
  </NuxtLink>
</template>

<script setup lang="ts">
export interface MediaYearTagProps {
  year: number
}

defineProps<MediaYearTagProps>()
</script>

<style lang="scss">
@use './styles/MediaYearTag.styles' as *;
</style>
