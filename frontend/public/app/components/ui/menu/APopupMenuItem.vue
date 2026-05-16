<template>
  <div class="a-popup-menu__item" @click="emit('click', $event)">
    <div v-if="$slots.icon" class="a-popup-menu__item-icon">
      <slot name="icon" />
    </div>
    <div v-if="$slots.label" class="a-popup-menu__item-label">
      <slot name="label" />
    </div>
    <div v-if="$slots.content" class="a-popup-menu__item-content">
      <slot name="content" />
    </div>
  </div>
</template>

<script lang="ts" setup>
export interface APopupMenuItemEmits {
  click: [event: Event]
}

const emit = defineEmits<APopupMenuItemEmits>()
</script>
