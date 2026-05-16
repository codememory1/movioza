<template>
  <div class="a-slider" :class="{ 'a-slider--ready': isReady }">
    <button
      type="button"
      :aria-controls="id"
      aria-label="Показать предыдущие слайды"
      class="a-slider__nav a-slider__nav--prev"
      :disabled="current === 0"
      @click="prev"
    >
      <AChevronLeftIcon />
    </button>
    <div :id ref="container" class="keen-slider a-slider__container">
      <div v-for="slide in slides" :key="slide[idKey]" class="keen-slider__slide a-slider__slide">
        <slot :slide="slide" />
      </div>
    </div>
    <button
      type="button"
      :aria-controls="id"
      aria-label="Показать предыдущие слайды"
      class="a-slider__nav a-slider__nav--next"
      :disabled="current === max"
      @click="next"
    >
      <AChevronRightIcon />
    </button>
  </div>
</template>

<script lang="ts" setup>
import { useKeenSlider } from 'keen-slider/vue'
import type { KeenSliderInstance } from 'keen-slider'
import AChevronLeftIcon from '~/components/ui/icon/AChevronLeftIcon.vue'
import AChevronRightIcon from '~/components/ui/icon/AChevronRightIcon.vue'

export interface ASliderProps {
  slides: any[]
  idKey: string
  perView?: number
  spacing?: number
}

const props = withDefaults(defineProps<ASliderProps>(), {
  perView: 5.5,
  spacing: 16
})
const id = useId()
const isReady = ref<boolean>(false)
const current = ref<number>(0)
const max = ref<number>(0)
const [container, slider] = useKeenSlider(
  {
    drag: true,
    mode: 'snap',
    rubberband: false,
    slides: {
      perView: props.perView,
      spacing: props.spacing
    },
    created(slider) {
      isReady.value = true
      current.value = slider.track.details.rel
      max.value = slider.track.details.maxIdx
    },
    slideChanged(slider) {
      current.value = slider.track.details.rel
    },
    updated(slider) {
      max.value = slider.track.details.maxIdx
    }
  },
  [WheelControls]
)

function WheelControls(slider: KeenSliderInstance) {
  let touchTimeout: NodeJS.Timeout | null = null
  const position = { x: 0, y: 0 }
  let wheelActive: boolean = false

  function dispatch(e: WheelEvent, name: string): void {
    position.x -= e.deltaX
    position.y -= e.deltaY

    slider.container.dispatchEvent(
      new CustomEvent(name, {
        detail: {
          x: position.x,
          y: position.y
        }
      })
    )
  }

  function wheelStart(e: WheelEvent) {
    position.x = e.pageX
    position.y = e.pageY

    dispatch(e, 'ksDragStart')
  }

  function wheel(e: WheelEvent) {
    dispatch(e, 'ksDrag')
  }

  function wheelEnd(e: WheelEvent) {
    dispatch(e, 'ksDragEnd')
  }

  function eventWheel(e: WheelEvent) {
    const isHorizontalScroll = Math.abs(e.deltaX) > Math.abs(e.deltaY)

    if (!isHorizontalScroll) {
      return
    }

    e.preventDefault()

    if (!wheelActive) {
      wheelStart(e)

      wheelActive = true
    }

    wheel(e)

    clearTimeout(touchTimeout!)

    touchTimeout = setTimeout(() => {
      wheelActive = false

      wheelEnd(e)
    }, 50)
  }

  slider.on('created', () => {
    slider.container.addEventListener('wheel', eventWheel, {
      passive: false
    })
  })
}

function next() {
  if (!slider.value) {
    return
  }

  const details = slider.value.track.details

  slider.value.moveToIdx(Math.min(details.rel + props.perView, details.maxIdx))
}

function prev() {
  if (!slider.value) {
    return
  }

  const details = slider.value.track.details

  slider.value.moveToIdx(Math.max(details.rel - props.perView, details.minIdx))
}
</script>

<style lang="scss">
@use './styles/ASlider.styles' as *;
</style>
