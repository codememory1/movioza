<template>
  <section class="media-filters-group" :class="{ 'media-filters-group--open': isOpen }">
    <button class="media-filters-group__header" type="button" @click="isOpen = !isOpen">
      {{ header }} <AChevronDownIcon />
    </button>

    <div v-if="isOpen" class="media-filters-group__panel">
      <div class="media-filters-group__panel-inner">
        <div v-if="$slots.search" class="media-filters-group__search">
          <slot name="search" />
        </div>
        <slot name="list">
          <div class="media-filters-group__options">
            <slot />
          </div>
        </slot>
      </div>
    </div>
  </section>
</template>

<script lang="ts" setup>
import AChevronDownIcon from '~/components/ui/icon/AChevronDownIcon.vue'

export interface MediaFiltersGroupProps {
  header: string
}

defineProps<MediaFiltersGroupProps>()

const isOpen = ref<boolean>(true)
</script>

<style lang="scss">
@use './styles/MediaFiltersGroup.styles' as *;
</style>
