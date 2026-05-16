import { type Ref } from 'vue'

interface UseRangeOptions {
  events?: {
    onPointerUpBefore?: (e: PointerEvent) => boolean
    onPointerUpAfter?: (e: PointerEvent) => void
    onPointerDownBefore?: (e: PointerEvent) => boolean
    onPointerDownAfter?: (e: PointerEvent) => void
    onPointerMoveBefore?: (e: PointerEvent) => boolean
    onPointerMoveAfter?: (e: PointerEvent) => void
    onPointerLeaveBefore?: (e: PointerEvent) => boolean
    onPointerLeaveAfter?: (e: PointerEvent) => void
    onPointerCancelBefore?: (e: PointerEvent) => boolean
    onPointerCancelAfter?: (e: PointerEvent) => void
    onLostPointerCaptureBefore?: (e: PointerEvent) => boolean
    onLostPointerCaptureAfter?: (e: PointerEvent) => void
    onDragging?: () => void
    onUnDragging?: () => void
    onHover?: (clientX: number) => void
    onUnHover?: () => void
    onUpdateHoverRatio?: (percent: number, clientX: number, e: PointerEvent) => void
    onUpdateDragRatio?: (percent: number, clientX: number, e: PointerEvent) => void
  }
}

export const useRange = (el: Ref<HTMLElement | undefined>, options: UseRangeOptions) => {
  const isDragging = ref<boolean>(false)
  const activePointerId = ref<number | null>(null)
  const elRect = computed(() => el.value?.getBoundingClientRect())

  const clamp = (value: number, min: number, max: number): number => {
    return Math.min(Math.max(value, min), max)
  }

  const getRatioByClientX = (clientX: number): number => {
    const rect = elRect.value

    if (!rect || rect.width <= 0) {
      return 0
    }

    const relativeX = clamp(clientX - rect.left, 0, rect.width)

    return relativeX / rect.width
  }

  const finishDragging = (): void => {
    if (!isDragging.value) {
      return
    }

    isDragging.value = false
    activePointerId.value = null
  }

  const onPointerDown = (event: PointerEvent): void => {
    if (!el.value) {
      return
    }

    if (false === options?.events?.onPointerDownBefore?.(event)) {
      return
    }

    event.preventDefault()

    isDragging.value = true
    activePointerId.value = event.pointerId

    el.value.focus()
    el.value.setPointerCapture(event.pointerId)

    options?.events?.onUpdateDragRatio?.(
      getRatioByClientX(event.clientX) * 100,
      event.clientX,
      event
    )
    options?.events?.onPointerDownAfter?.(event)
  }

  const onPointerUp = (event: PointerEvent): void => {
    if (!el.value) {
      finishDragging()

      return
    }

    if (false === options?.events?.onPointerUpBefore?.(event)) {
      return
    }

    if (activePointerId.value !== event.pointerId) {
      return
    }

    if (el.value.hasPointerCapture(event.pointerId)) {
      el.value.releasePointerCapture(event.pointerId)
    }

    finishDragging()

    options?.events?.onPointerUpAfter?.(event)
  }

  const onPointerMove = (event: PointerEvent): void => {
    if (false === options?.events?.onPointerMoveBefore?.(event)) {
      return
    }

    options?.events?.onHover?.(event.clientX)
    options?.events?.onUpdateHoverRatio?.(
      getRatioByClientX(event.clientX) * 100,
      event.clientX,
      event
    )

    if (activePointerId.value !== event.pointerId) {
      return
    }

    if (isDragging.value) {
      options?.events?.onUpdateDragRatio?.(
        getRatioByClientX(event.clientX) * 100,
        event.clientX,
        event
      )
    }

    options?.events?.onPointerMoveAfter?.(event)
  }

  const onPointerCancel = (event: PointerEvent): void => {
    if (!el.value) {
      finishDragging()

      return
    }

    if (false === options?.events?.onPointerCancelBefore?.(event)) {
      return
    }

    if (activePointerId.value !== event.pointerId) {
      return
    }

    if (el.value.hasPointerCapture(event.pointerId)) {
      el.value.releasePointerCapture(event.pointerId)
    }

    finishDragging()

    options?.events?.onPointerCancelAfter?.(event)
  }

  const onLostPointerCapture = (event: PointerEvent): void => {
    if (false === options?.events?.onLostPointerCaptureBefore?.(event)) {
      return
    }

    finishDragging()

    options?.events?.onLostPointerCaptureAfter?.(event)
  }

  const onPointerLeave = (event: PointerEvent): void => {
    if (false === options?.events?.onPointerLeaveBefore?.(event)) {
      return
    }

    options?.events?.onUnHover?.()
    options?.events?.onPointerLeaveAfter?.(event)
  }

  onBeforeUnmount(() => {
    finishDragging()
  })

  watch(
    () => isDragging.value,
    () => {
      if (isDragging.value) {
        options?.events?.onDragging?.()
      } else {
        options?.events?.onUnDragging?.()
      }
    }
  )

  return {
    isDragging,
    activePointerId,
    elRect,
    getRatioByClientX,
    finishDragging,
    listeners: {
      pointerup: onPointerUp,
      pointerdown: onPointerDown,
      pointermove: onPointerMove,
      pointercancel: onPointerCancel,
      pointerleave: onPointerLeave,
      lostpointercapture: onLostPointerCapture
    }
  }
}
