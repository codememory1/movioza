<template>
  <div
    class="a-popup-menu"
    :class="{ 'a-popup-menu--visible': visible }"
    :style="{
      width: `${viewSize.width}px`,
      height: `${viewSize.height}px`
    }"
  >
    <transition :name="transitionName">
      <div :key="activeViewId" class="a-popup-menu__view">
        <div v-if="currentView!.header" class="a-popup-menu__header">
          <button class="a-popup-menu__prev-btn" @click="onPrevClick">
            <span class="a-popup-menu__prev-btn-icon">
              <AChevronLeftIcon />
            </span>

            {{ currentView!.header }}
          </button>
          <span v-if="currentView!.hint" class="a-popup-menu__hint">{{ currentView!.hint }}</span>
        </div>
        <div class="a-popup-menu__items" role="menu">
          <slot :name="currentView!.id" :goTo="goTo" :goBack="goBack" />
        </div>
      </div>
    </transition>
  </div>
</template>

<script lang="ts" setup>
import AChevronLeftIcon from '~/components/ui/icon/AChevronLeftIcon.vue'

type APopupMenuTransition = 'menu-back-animation' | 'menu-forward-animation'

export interface APopupMenuView {
  id: string
  header?: string
  hint?: string | number
  width: number
  height: number
}

export interface APopupMenuProps {
  visible: boolean
  views: APopupMenuView[]
  defaultViewId: string
}

const props = defineProps<APopupMenuProps>()
const views = computed(() => {
  const map = new Map<string, APopupMenuView>()

  props.views.forEach((view) => {
    map.set(view.id, view)
  })

  return map
})
const transitionName = ref<APopupMenuTransition>('menu-forward-animation')
const activeViewId = ref<string>(props.defaultViewId)
const currentView = computed(() => views.value.get(activeViewId.value))
const pipelineViews = ref<string[]>([props.defaultViewId])
const viewSize = reactive({
  width: currentView.value?.width ?? 0,
  height: currentView.value?.height ?? 0
})

const goTo = (viewId: string): void => {
  transitionName.value = 'menu-forward-animation'
  activeViewId.value = viewId

  pipelineViews.value.push(viewId)

  requestAnimationFrame(() => {
    const view = views.value.get(viewId)

    viewSize.width = view!.width
    viewSize.height = view!.height
  })
}

const goBack = (): void => {
  transitionName.value = 'menu-back-animation'

  pipelineViews.value.pop()

  activeViewId.value = pipelineViews.value[pipelineViews.value.length - 1]!

  viewSize.width = currentView.value!.width
  viewSize.height = currentView.value!.height
}

const onPrevClick = (): void => {
  goBack()
}

defineExpose({
  goTo,
  goBack
})
</script>

<style lang="scss">
@use './styles/APopupMenu.styles' as *;
</style>
