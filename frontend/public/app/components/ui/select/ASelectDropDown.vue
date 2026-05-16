<template>
  <AOverlay
    v-model="model"
    ref="dropDownRef"
    :reference
    class="a-select-drop-down"
    :class="{ 'a-select-drop-down--visible': model }"
  >
    <div class="a-select-drop-down__list-container">
      <ul :id role="listbox" class="a-select-drop-down__list">
        <ASelectOption
          v-for="(option, i) in options"
          :key="option[optionValue]"
          :option
          :option-value
          :option-label
          :selected="isSelected(option)"
          :setsize="options.length"
          :posinset="i + 1"
          @click="emit('selectedOption', option)"
        />
      </ul>
    </div>
  </AOverlay>
</template>

<script lang="ts" setup>
import ASelectOption from '@/components/ui/select/ASelectOption.vue'
import AOverlay from '@/components/ui/overlay/AOverlay.vue'

export interface ASelectDropDownProps {
  id: string
  multiple?: boolean
  reference: HTMLElement | undefined
  options: any
  optionValue: string
  optionLabel: string
  selected: any | any[]
}

export interface ASelectDropDownEmits {
  selectedOption: [option: any]
}

const props = withDefaults(defineProps<ASelectDropDownProps>(), {
  multiple: false
})
const emit = defineEmits<ASelectDropDownEmits>()
const model = defineModel<boolean>({ required: true })
const selectedValues = computed<any | any[]>(() => {
  if (props.multiple) {
    return (props.selected as any[]).map((option) => option[props.optionValue])
  }

  return props.selected ? props.selected[props.optionValue] : props.selected
})

const isSelected = (option: any): boolean => {
  if (!props.multiple) {
    return props.selected && selectedValues.value === option[props.optionValue]
  }

  return selectedValues.value.includes(option[props.optionValue])
}
</script>

<style lang="scss">
@use './styles/ASelectDropDown.styles' as *;
</style>
