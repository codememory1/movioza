<template>
  <div class="a-checkbox" :class="{ [`a-checkbox--${variant}`]: true }">
    <input
      v-model="model"
      :id
      type="checkbox"
      class="a-checkbox__checkbox"
      :aria-label="ariaLabel"
    />
    <label v-if="label" :for="id" class="a-checkbox__label">{{ label }}</label>
  </div>
</template>

<script lang="ts" setup>
export interface ACheckboxProps {
  ariaLabel: string
  label?: string
  variant?: 'switch' | 'default'
}

withDefaults(defineProps<ACheckboxProps>(), {
  variant: 'default'
})

const model = defineModel<boolean>({ required: true })
const id = useId()
</script>

<style lang="scss">
@use './styles/ACheckbox.styles' as *;
</style>
