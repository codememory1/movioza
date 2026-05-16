<template>
  <div
    class="a-range-hover-bar"
    :class="{ hovering: isHovering }"
    :style="{ width: `${ratio}%` }"
  />
</template>

<script lang="ts" setup>
export interface ARangeHoverBarProps {
  isHovering: boolean
  ratio: number
}

defineProps<ARangeHoverBarProps>()
</script>

<style lang="scss">
@use './styles/ARangeHoverBar.styles' as *;
</style>
