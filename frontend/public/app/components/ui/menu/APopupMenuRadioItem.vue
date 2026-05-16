<template>
  <APopupMenuItem
    class="a-popup-menu__radio-item"
    role="menuitemradio"
    :aria-checked="checked"
    tabindex="0"
    @click="emit('click', $event)"
  >
    <template #icon>
      <ACheckIcon v-if="checked" />
    </template>

    <template #label>
      <slot name="label" :label="label">{{ label }}</slot>
    </template>
  </APopupMenuItem>
</template>

<script lang="ts" setup>
import APopupMenuItem, { type APopupMenuItemEmits } from '~/components/ui/menu/APopupMenuItem.vue'
import ACheckIcon from '~/components/ui/icon/ACheckIcon.vue'

export interface APopupMenuRadioItemProps {
  checked: boolean
  label: string | number
}

export interface APopupMenuRadioItemEmits extends APopupMenuItemEmits {}

defineProps<APopupMenuRadioItemProps>()

const emit = defineEmits<APopupMenuRadioItemEmits>()
</script>
