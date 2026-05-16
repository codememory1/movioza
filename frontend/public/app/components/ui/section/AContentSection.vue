<template>
  <section class="a-content-section" :aria-labelledby="id">
    <slot name="beforeHeader" />

    <header class="a-content-section__header">
      <h2 :id class="a-content-section__title">{{ title }}</h2>
      <div v-if="appendLink || $slots.appendHeader" class="a-content-section__header-append">
        <slot name="appendHeader">
          <NuxtLink v-if="appendLink" :to="appendLink.to" class="a-content-section__append-link">
            {{ appendLink.label }}
          </NuxtLink>
        </slot>
      </div>
    </header>
    <div class="a-content-section__content" :class="contentClass">
      <slot />
    </div>
  </section>
</template>

<script lang="ts" setup>
import type { RouteLocationRaw } from 'vue-router'

export interface AContentSectionProps {
  title: string
  contentClass?: string
  appendLink?: {
    to: RouteLocationRaw
    label: string
  }
}

defineProps<AContentSectionProps>()

const id = useId()
</script>

<style lang="scss">
@use './styles/AContentSection.styles' as *;
</style>
