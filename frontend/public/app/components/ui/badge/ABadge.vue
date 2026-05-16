<template>
  <component :is="as" class="a-badge" v-bind="attrs" :aria-label="label">
    <slot />
  </component>
</template>

<script lang="ts" setup>
export interface ABadgeProps {
  as?: string | Component
  label?: string
}

withDefaults(defineProps<ABadgeProps>(), {
  as: 'span'
})

const attrs = useAttrs()
</script>

<style lang="scss">
@use './styles/ABadge.styles' as *;
</style>
