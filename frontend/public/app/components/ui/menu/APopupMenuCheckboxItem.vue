<template>
  <APopupMenuItem
    class="a-popup-menu__checkbox-item"
    role="menuitemcheckbox"
    :aria-checked="checked"
    tabindex="0"
    @click="emit('click', $event)"
  >
    <template #icon>
      <component :is="icon" />
    </template>
    <template #label>
      {{ label }}
      <span v-if="hint" class="a-popup-menu__checkbox-item-hint">{{ hint }}</span>
    </template>
    <template #content>
      <ACheckbox
        :model-value="checked"
        variant="switch"
        ariaLabel="Фоновый режим"
        @update:model-value="emit('updateChecked', $event)"
      />
    </template>
  </APopupMenuItem>
</template>

<script lang="ts" setup>
import APopupMenuItem, { type APopupMenuItemEmits } from '~/components/ui/menu/APopupMenuItem.vue'
import ACheckbox from '~/components/ui/checkbox/ACheckbox.vue'

export interface APopupMenuCheckboxItemProps {
  icon: Component
  label: string | number
  hint?: string
  checked: boolean
}

export interface APopupMenuCheckboxItemEmits extends APopupMenuItemEmits {
  updateChecked: [checked: boolean]
}

defineProps<APopupMenuCheckboxItemProps>()

const emit = defineEmits<APopupMenuCheckboxItemEmits>()
</script>

<style lang="scss">
@use './styles/APopupMenuCheckboxItem.styles' as *;
</style>
