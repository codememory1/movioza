<template>
  <APopupMenuItem
    class="a-popup-menu__navigation-item"
    aria-haspopup="true"
    role="menuitem"
    tabindex="0"
    @click="emit('click', $event)"
  >
    <template #icon>
      <component :is="icon" />
    </template>
    <template #label>{{ label }}</template>
    <template #content>
      <slot name="fullContent" :content="content">
        <slot name="content" :content="content">
          {{ content }}
        </slot>
        <AChevronRightIcon />
      </slot>
    </template>
  </APopupMenuItem>
</template>

<script lang="ts" setup>
import APopupMenuItem, { type APopupMenuItemEmits } from '~/components/ui/menu/APopupMenuItem.vue'
import AChevronRightIcon from '~/components/ui/icon/AChevronRightIcon.vue'

export interface APopupMenuNavigationItemProps {
  icon: Component
  label: string | number
  content: string | number
}

export interface APopupMenuNavigationItemEmits extends APopupMenuItemEmits {}

defineProps<APopupMenuNavigationItemProps>()

const emit = defineEmits<APopupMenuNavigationItemEmits>()
</script>
