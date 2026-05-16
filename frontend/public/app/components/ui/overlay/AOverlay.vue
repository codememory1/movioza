<template>
  <transition @enter="onTransitionEnter" @leave="onTransitionLeave">
    <component
      ref="floatingRef"
      v-show="model"
      :is="tag"
      v-bind="$attrs"
      :style="{
        ...floatingStyles,
        width: `${middlewareState?.rects.reference.width}px`
      }"
      :class="{ [`a-overlay--${placement}`]: true }"
    >
      <slot />
    </component>
  </transition>
</template>

<script lang="ts" setup>
import {
  useFloating,
  autoUpdate,
  offset as middlewareOffset,
  flip as middlewareFlip,
  shift as middlewareShift,
  size as middlewareSize,
  hide as middlewareHide,
  type MiddlewareState,
  type Placement
} from '@floating-ui/vue'
import {
  enterBottomAnimation,
  enterLeftAnimation,
  enterRightAnimation,
  enterTopAnimation,
  leaveBottomAnimation,
  leaveLeftAnimation,
  leaveRightAnimation,
  leaveTopAnimation
} from './animation'

export interface AOverlayProps {
  reference: HTMLElement | undefined
  tag?: string
  placement?: Placement
  offset?: number
}

defineOptions({
  inheritAttrs: false
})

const props = withDefaults(defineProps<AOverlayProps>(), {
  tag: 'div',
  placement: 'bottom',
  offset: 5
})
const model = defineModel<boolean>({ required: true })
const floatingRef = ref<HTMLElement>()
const middlewareState = ref<MiddlewareState>()
const reference = computed<HTMLElement | undefined>(() => props.reference)
const { placement, floatingStyles, middlewareData } = useFloating(reference, floatingRef, {
  middleware: [
    middlewareOffset(props.offset),
    middlewareFlip(),
    middlewareShift(),
    middlewareSize({
      apply(state) {
        middlewareState.value = state
      }
    }),
    middlewareHide({ strategy: 'referenceHidden' })
  ],
  transform: false,
  placement: props.placement,
  whileElementsMounted: autoUpdate
})
const shouldHideByFloating = computed<boolean>(() => {
  const hide = middlewareData.value.hide

  if (!hide) {
    return false
  }

  return hide.referenceHidden === true || (hide.referenceHiddenOffsets?.top ?? 0) >= -64
})

watch(
  () => shouldHideByFloating.value,
  (newValue) => {
    if (newValue) {
      model.value = false
    }
  }
)

const onTransitionEnter = (el: Element, done: () => void): void => {
  if (placement.value.startsWith('top')) {
    enterTopAnimation(el, done)
  }

  if (placement.value.startsWith('bottom')) {
    enterBottomAnimation(el, done)
  }

  if (placement.value.startsWith('left')) {
    enterLeftAnimation(el, done)
  }

  if (placement.value.startsWith('right')) {
    enterRightAnimation(el, done)
  }
}

const onTransitionLeave = (el: Element, done: () => void): void => {
  if (placement.value.startsWith('top')) {
    leaveTopAnimation(el, done)
  }

  if (placement.value.startsWith('bottom')) {
    leaveBottomAnimation(el, done)
  }

  if (placement.value.startsWith('left')) {
    leaveLeftAnimation(el, done)
  }

  if (placement.value.startsWith('right')) {
    leaveRightAnimation(el, done)
  }
}
</script>
