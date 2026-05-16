<template>
  <div
    ref="rangeRef"
    class="a-range"
    :aria-valuemin="0"
    :aria-valuemax="max"
    :aria-valuenow="value"
    tabindex="0"
    v-on="listeners"
  >
    <div v-if="tooltip" class="a-range__tooltip" :class="{ show: isHover }">
      <slot name="tooltip" :value="tooltipValue" :left="hoverRatio">
        <span class="a-range__tooltip-value" :style="{ left: `${hoverRatio}%` }">
          <slot name="tooltipValue" :value="tooltipValue">
            {{ Math.round(tooltipValue) }}
          </slot>
        </span>
      </slot>
    </div>

    <div class="a-range__bars">
      <slot name="prependBar" />

      <ARangeBackBar />
      <ARangeHoverBar :is-hovering="isHover" :ratio="hoverRatio" />
      <ARangeValueBar :ratio="dragRatio" />

      <slot name="appendBar" />
    </div>

    <div v-if="point" class="a-range__point-wrapper">
      <slot name="point">
        <ARangePoint :dragging="isDragging" :ratio="dragRatio" />
      </slot>
    </div>
  </div>
</template>

<script lang="ts" setup>
import ARangeBackBar from '~/components/ui/range/ARangeBackBar.vue'
import ARangeHoverBar from '~/components/ui/range/ARangeHoverBar.vue'
import ARangeValueBar from '~/components/ui/range/ARangeValueBar.vue'
import ARangePoint from '~/components/ui/range/ARangePoint.vue'
import { useRange } from './composables/useRange'

export interface ARangeProps {
  max: number
  value: number
  tooltip?: boolean
  point?: boolean
}

export interface ARangeEmits {
  (e: 'updateDragging', isDragging: boolean): void
  (e: 'updateValue', value: number): void
  (e: 'release', value: number): void
}

const props = withDefaults(defineProps<ARangeProps>(), {
  tooltip: true,
  point: true
})
const emit = defineEmits<ARangeEmits>()
const rangeRef = ref<HTMLElement>()
const hoverRatio = ref<number>(0)
const dragRatio = ref<number>(0)
const isHover = ref<boolean>(false)
const isDragging = ref<boolean>(false)
const hoverClientX = ref<number>(0)
const tooltipValue = computed<number>(() => (props.max / 100) * hoverRatio.value)
const dragValue = computed<number>(() => (props.max / 100) * dragRatio.value)

watch(
  () => props.value,
  (value) => {
    dragRatio.value = props.max > 0 ? (value / props.max) * 100 : 0
  },
  { immediate: true }
)

const { listeners, elRect } = useRange(rangeRef, {
  events: {
    onDragging() {
      isDragging.value = true

      emit('updateDragging', true)
    },
    onUnDragging() {
      isDragging.value = false

      emit('updateDragging', false)
    },
    onHover(clientX: number) {
      isHover.value = true
      hoverClientX.value = clientX - elRect.value!.left
    },
    onUnHover() {
      isHover.value = false
    },
    onUpdateHoverRatio(percent: number) {
      hoverRatio.value = percent
    },
    onUpdateDragRatio(percent: number) {
      dragRatio.value = percent

      emit('updateValue', dragValue.value)
    },
    onPointerUpAfter() {
      emit('release', dragValue.value)
    }
  }
})
</script>

<style lang="scss">
@use './styles/ARange.styles' as *;
</style>
