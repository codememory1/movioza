<template>
  <div class="a-rating" role="radiogroup" aria-label="Выберите оценку">
    <button
      v-for="i in labels.length"
      :key="i"
      type="button"
      role="radio"
      :aria-checked="i === model"
      class="a-rating-item"
      :class="{
        'a-rating-item--hover': i <= hoverIndex,
        'a-rating-item--selected': i <= model
      }"
      :aria-label="labels[i - 1]"
      :tabindex="i === model ? 0 : -1"
      @pointerenter="onPointerEnter(i)"
      @pointerleave="onPointerLeave"
      @click="onSelect(i)"
    >
      <slot v-if="i <= model && $slots.selectedItem" name="selectedItem" :num="i" />
      <slot v-else name="item" :num="i" />
    </button>
  </div>
</template>

<script lang="ts" setup>
export interface ARatingProps {
  labels: string[]
}

export interface ARatingEmits {
  select: [num: number]
}

export interface ARatingSlots {
  item(scope: { num: number }): VNode
  selectedItem(scope: { num: number }): VNode
}

defineProps<ARatingProps>()

const emit = defineEmits<ARatingEmits>()

defineSlots<ARatingSlots>()

const model = defineModel<number>({ required: true })
const hoverIndex = ref<number>(0)

const onPointerEnter = (index: number): void => {
  hoverIndex.value = index
}

const onPointerLeave = (): void => {
  hoverIndex.value = 0
}

const onSelect = (index: number): void => {
  model.value = index

  emit('select', index)
}
</script>
