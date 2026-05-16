<template>
  <div class="a-range-value-bar" :style="{ width: `${ratio}%` }" />
</template>

<script lang="ts" setup>
export interface ARangeValueBarProps {
  ratio: number
}

defineProps<ARangeValueBarProps>()
</script>

<style lang="scss">
@use './styles/ARangeValueBar.styles' as *;
</style>
