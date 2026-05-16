<template>
  <APopupMenuItem
    class="a-popup-menu__button-item"
    role="menuitem"
    tabindex="0"
    @click="emit('click', $event)"
  >
    <template #icon>
      <component :is="icon" />
    </template>
    <template #label>
      <slot name="label" :label="label">{{ label }}</slot>
    </template>
  </APopupMenuItem>
</template>

<script lang="ts" setup>
import APopupMenuItem, { type APopupMenuItemEmits } from '~/components/ui/menu/APopupMenuItem.vue'

export interface APopupMenuButtonItemProps {
  icon: Component
  label: string | number
}

export interface APopupMenuButtonItemEmits extends APopupMenuItemEmits {}

defineProps<APopupMenuButtonItemProps>()

const emit = defineEmits<APopupMenuButtonItemEmits>()
</script>
