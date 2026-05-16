<template>
  <div v-bind="containerProps" class="a-virtual-scroll">
    <div v-bind="wrapperProps" class="a-virtual-scroll__wrapper">
      <slot
        v-for="(item, index) in list"
        :key="item.data[idField] || index"
        :item="item.data"
        :index="item.index"
      />
    </div>
  </div>
</template>

<script lang="ts" setup>
import { useVirtualList, type UseVirtualListOptions } from '@vueuse/core'

export interface AVirtualScrollProps {
  items: any[]
  idField: string
  options: UseVirtualListOptions
}

export interface AVirtualScrollEmits {
  end: []
}

const props = defineProps<AVirtualScrollProps>()
const emit = defineEmits<AVirtualScrollEmits>()
const items = computed<any[]>(() => props.items)
const { list, containerProps, wrapperProps, scrollTo } = useVirtualList(items, props.options)

watchEffect(() => {
  const lastVisibleItem = list.value.at(-1)

  if (!lastVisibleItem) {
    return
  }

  const overscan = props.options.overscan || 0

  if (lastVisibleItem.index >= items.value.length - 1 - overscan) {
    emit('end')
  }
})

defineExpose({ scrollTo })
</script>
