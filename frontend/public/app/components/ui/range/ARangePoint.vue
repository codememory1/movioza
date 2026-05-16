<template>
  <div class="a-range-point" :class="{ dragging }" :style="{ left: `${ratio}%` }" />
</template>

<script setup lang="ts">
export interface ARangePointProps {
  dragging?: boolean
  ratio: number
}

withDefaults(defineProps<ARangePointProps>(), {
  dragging: false
})
</script>

<style lang="scss">
@use './styles/ARangePoint.styles' as *;
</style>
