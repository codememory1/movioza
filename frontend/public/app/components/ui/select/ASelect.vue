<template>
  <div
    ref="selectRef"
    class="a-select"
    :class="{ 'a-select--visible': visible }"
    @click="onToggleSelect"
  >
    <div v-if="prependIcon || $slots.prepend" class="a-select__prepend">
      <slot name="prepend">
        <component v-if="prependIcon" :is="prependIcon" />
      </slot>
    </div>
    <span
      :aria-label="ariaLabel"
      aria-haspopup="listbox"
      :aria-expanded="false"
      :aria-controls="id"
      aria-disabled="false"
      class="a-select__view-box"
      :class="{ placeholder: !selected }"
    >
      {{ viewBoxValue }}
    </span>
    <div class="a-select__append">
      <slot name="append" :visible="visible">
        <div class="a-select__drop-down-icon">
          <AChevronDownIcon />
        </div>
      </slot>
    </div>
    <teleport :to="teleportTo">
      <ASelectDropDown
        v-model="visible"
        ref="dropDownRef"
        :options
        :id
        :multiple
        :option-label
        :option-value
        :selected
        :reference="selectRef"
        @selected-option="onSelectedOption"
      />
    </teleport>
  </div>
</template>

<script lang="ts" setup>
import { onClickOutside } from '@vueuse/core'
import ASelectDropDown from '~/components/ui/select/ASelectDropDown.vue'
import AChevronDownIcon from '~/components/ui/icon/AChevronDownIcon.vue'

export interface ASelectProps {
  label?: string
  placeholder: string
  optionLabel: string
  optionValue: string
  options: any[]
  multiple?: boolean
  prependIcon?: Component
  teleportTo?: string
}

const props = withDefaults(defineProps<ASelectProps>(), {
  optionLabel: 'label',
  optionValue: 'value',
  multiple: false,
  teleportTo: 'body'
})
const model = defineModel<any | any[]>({ required: true })
const id = useId()
const selectRef = ref<HTMLDivElement>()
const dropDownRef = ref<HTMLElement>()
const selected = computed<any | any[]>(() => {
  if (!props.multiple) {
    return props.options.find((option) => option[props.optionValue] === model.value)
  }

  return props.options.filter((option) => model.value.includes(option[props.optionValue]))
})
const viewBoxValue = computed<string>(() => {
  if (model.value === null) {
    return props.placeholder
  }

  if (props.multiple) {
    if (!Array.isArray(selected.value) || selected.value.length === 0) {
      return props.placeholder
    }

    return selected.value.map((option) => option[props.optionLabel]).join(', ')
  }

  return selected.value?.[props.optionLabel] ?? props.placeholder
})
const ariaLabel = computed<string>(() => props.label || props.placeholder)
const visible = ref<boolean>(false)

onClickOutside(
  dropDownRef,
  () => {
    visible.value = false
  },
  { ignore: [selectRef] }
)

const onToggleSelect = (): void => {
  visible.value = !visible.value
}

const onSelectedOption = (option: any): void => {
  const value = option[props.optionValue]

  if (!props.multiple) {
    model.value = value

    visible.value = false
  } else {
    const index = model.value.indexOf(value)

    if (index === -1) {
      model.value.push(value)
    } else {
      model.value.splice(index, 1)
    }
  }
}
</script>

<style lang="scss">
@use './styles/ASelect.styles' as *;
</style>
