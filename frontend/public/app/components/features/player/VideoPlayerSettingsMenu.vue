<template>
  <APopupMenu
    :visible
    :views
    ref="popupMenuRef"
    default-view-id="main"
    class="video-player-settings-menu"
  >
    <template #main="{ goTo }">
      <APopupMenuCheckboxItem
        :icon="ASparklesOutlineIcon"
        label="Фоновый режим"
        hint="Создаётся размытый фон вокруг видео"
        :checked="activeAmbient"
        @click="onAmbientToggle(!activeAmbient)"
        @update-checked="onAmbientToggle"
      />
      <APopupMenuCheckboxItem
        :icon="AInfinityIcon"
        label="Режим Марафона"
        hint="Пропускаются все заставки, титры и повторы"
        :checked="marathonMode"
        @click="marathonMode = !marathonMode"
        @update-checked="marathonMode = $event"
      />
      <APopupMenuNavigationItem
        :content="qualityOptions.get(activeQuality)!.label"
        :icon="AHDOutlineIcon"
        label="Качество"
        @click="goTo('qualities')"
      >
        <template #content="{ content }">
          {{ content }} {{ qualityOptions.get(activeQuality)!.tag }}
        </template>
      </APopupMenuNavigationItem>
      <APopupMenuNavigationItem
        :content="playbackSpeedOptions.get(activePlaybackSpeed)!.label"
        :icon="ASpeedOutlineIcon"
        label="Скорость"
        @click="goTo('playbackSpeeds')"
      />
      <APopupMenuNavigationItem
        :content="`${activeScale}%`"
        :icon="AScalingOutlineIcon"
        label="Масштаб"
        @click="goTo('scale')"
      />
    </template>
    <template #qualities="{ goBack }">
      <APopupMenuRadioItem
        v-for="option in qualityOptions.values()"
        :checked="option.value === activeQuality"
        :label="option.label"
        class="video-player-settings-menu__quality-item"
        @click="onQualityClick(option, goBack)"
      >
        <template #label="{ label }">
          {{ label }}
          <span v-if="option.tag" class="video-player-settings-menu__quality-tag">
            {{ option.tag }}
          </span>
        </template>
      </APopupMenuRadioItem>
    </template>
    <template #playbackSpeeds="{ goBack }">
      <APopupMenuRadioItem
        v-for="option in playbackSpeedOptions.values()"
        :checked="option.value === activePlaybackSpeed"
        :label="option.label"
        @click="onPlaybackSpeedClick(option, goBack)"
      />
    </template>
    <template #scale>
      <APopupMenuButtonItem
        :icon="AZoomInOutlineIcon"
        label="5%"
        @click="onScaleClick(activeScale + 5)"
      />
      <APopupMenuButtonItem
        :icon="AZoomOutOutlineIcon"
        label="5%"
        @click="onScaleClick(activeScale - 5)"
      />
      <APopupMenuButtonItem
        :icon="ARotateBackOutlineIcon"
        label="100%"
        @click="onScaleClick(100)"
      />
    </template>
  </APopupMenu>
</template>

<script lang="ts" setup>
import APopupMenu, { type APopupMenuView } from '~/components/ui/menu/APopupMenu.vue'
import AHDOutlineIcon from '~/components/ui/icon/AHDOutlineIcon.vue'
import ASpeedOutlineIcon from '~/components/ui/icon/ASpeedOutlineIcon.vue'
import AScalingOutlineIcon from '~/components/ui/icon/AScalingOutlineIcon.vue'
import {
  getResolutionLabel,
  getDynamicRangeLabel,
  getQualityTag
} from '~/components/features/player/utils/video-player.utils'
import APopupMenuRadioItem from '~/components/ui/menu/APopupMenuRadioItem.vue'
import APopupMenuNavigationItem from '~/components/ui/menu/APopupMenuNavigationItem.vue'
import APopupMenuCheckboxItem from '~/components/ui/menu/APopupMenuCheckboxItem.vue'
import ASparklesOutlineIcon from '~/components/ui/icon/ASparklesOutlineIcon.vue'
import APopupMenuButtonItem from '~/components/ui/menu/APopupMenuButtonItem.vue'
import AZoomInOutlineIcon from '~/components/ui/icon/AZoomInOutlineIcon.vue'
import AZoomOutOutlineIcon from '~/components/ui/icon/AZoomOutOutlineIcon.vue'
import ARotateBackOutlineIcon from '~/components/ui/icon/ARotateBackOutlineIcon.vue'
import type { Level } from 'hls.js'
import AInfinityIcon from '~/components/ui/icon/AInfinityIcon.vue'

export interface QualityOption {
  value: number
  label: string
  tag?: string | null
}

interface PlaybackSpeedOption {
  value: number
  label: string
}

export interface VideoPlayerSettingsMenuProps {
  visible: boolean
  qualityLevels: Level[]
}

export interface VideoPlayerSettingsMenuEmits {
  updateQuality: [quality: number]
  updatePlaybackSpeed: [speed: number]
  updateSleepTimer: [timer: number]
  updateScale: [scale: number]
  toggleAmbient: [active: boolean]
}

const props = defineProps<VideoPlayerSettingsMenuProps>()
const emit = defineEmits<VideoPlayerSettingsMenuEmits>()
const popupMenuRef = ref<InstanceType<typeof APopupMenu>>()
const activeAmbient = ref<boolean>(true)
const activePlaybackSpeed = ref<number>(1)
const activeQuality = ref<number>(-1)
const activeScale = ref<number>(100)
const marathonMode = defineModel<boolean>('marathonMode', { required: true })

const qualityOptions = computed<Map<QualityOption['value'], QualityOption>>(() => {
  const result: QualityOption[] = [
    { value: -1, label: 'Авто' },
    ...props.qualityLevels.map((level, index) => ({
      value: index,
      label: [getResolutionLabel(level), getDynamicRangeLabel(level)].join(' ').trim(),
      tag: getQualityTag(level)
    }))
  ]

  result.sort((a, b) => b.value - a.value)

  return new Map(result.map((option) => [option.value, option]))
})

const playbackSpeedOptions = computed<Map<PlaybackSpeedOption['value'], PlaybackSpeedOption>>(
  () =>
    new Map([
      [0.25, { value: 0.25, label: '0.25x' }],
      [0.5, { value: 0.5, label: '0.5x' }],
      [0.75, { value: 0.75, label: '0.75x' }],
      [1, { value: 1, label: 'Обычная' }],
      [1.25, { value: 1.25, label: '1.25x' }],
      [1.5, { value: 1.5, label: '1.5x' }],
      [2, { value: 2, label: '2x' }]
    ])
)

const views = computed<APopupMenuView[]>(() => [
  {
    id: 'main',
    width: 370,
    height: 250
  },
  {
    id: 'qualities',
    header: 'Качество',
    width: 250,
    height: 355
  },
  {
    id: 'playbackSpeeds',
    header: 'Скорость',
    width: 250,
    height: 355
  },
  {
    id: 'scale',
    header: 'Масштаб',
    hint: `${activeScale.value}%`,
    width: 250,
    height: 210
  }
])

watch(
  () => props.visible,
  () => {
    if (!props.visible && popupMenuRef.value) {
      popupMenuRef.value.goTo('main')
    }
  }
)

const onAmbientToggle = (checked: boolean): void => {
  activeAmbient.value = checked

  emit('toggleAmbient', activeAmbient.value)
}

const onQualityClick = (option: QualityOption, goBack: () => void): void => {
  activeQuality.value = option.value

  emit('updateQuality', option.value)

  goBack()
}

const onPlaybackSpeedClick = (option: PlaybackSpeedOption, goBack: () => void): void => {
  activePlaybackSpeed.value = option.value

  emit('updatePlaybackSpeed', option.value)

  goBack()
}

const onScaleClick = (percent: number): void => {
  if (percent < 5 || percent > 1000) {
    return
  }

  activeScale.value = percent

  emit('updateScale', activeScale.value / 100)
}
</script>

<style lang="scss">
@use './styles/VideoPlayerSettingsMenu.styles' as *;
</style>
