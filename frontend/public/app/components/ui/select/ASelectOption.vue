<template>
  <li
    role="option"
    :aria-label="option[optionLabel]"
    :aria-selected="selected"
    aria-disabled="false"
    :aria-setsize="setsize"
    :aria-posinset="posinset"
    class="a-select-option"
    @click="emit('click')"
  >
    {{ option[optionLabel] || option }}
  </li>
</template>

<script lang="ts" setup>
export interface ASelectOptionProps {
  option: any
  optionValue: string
  optionLabel: string
  selected: boolean
  setsize: number
  posinset: number
}

export interface ASelectOptionEmits {
  click: []
}

defineProps<ASelectOptionProps>()

const emit = defineEmits<ASelectOptionEmits>()
</script>

<style lang="scss">
@use './styles/ASelectOption.styles' as *;
</style>
