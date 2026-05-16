<template>
  <div
    class="a-input"
    :class="{
      'a-input--prepend': $slots.prepend || prependIcon,
      'a-input--append': $slots.append || appendIcon,
      'a-input--focus': focus
    }"
  >
    <div v-if="$slots.prepend || prependIcon" class="a-input__prepend">
      <slot name="prepend">
        <div class="a-input__icon-wrapper">
          <component :is="prependIcon" class="a-input__icon" />
        </div>
      </slot>
    </div>
    <input
      :id
      :type="type"
      :autocomplete="autocomplete"
      :placeholder="placeholder"
      :value="model"
      class="a-input__input"
      @input="onInput"
      @focus="focus = true"
      @blur="focus = false"
    />
    <div v-if="$slots.append && appendIcon" class="a-input__append">
      <slot name="append">
        <div class="a-input__icon-wrapper">
          <component :is="appendIcon" class="a-input__icon" />
        </div>
      </slot>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { isEmpty } from 'lodash'
import { type Component, computed, useId } from 'vue'

export interface AInputProps {
  id?: string
  type?: 'text' | 'password'
  name?: string
  placeholder: string
  prependIcon?: Component
  appendIcon?: Component
  autocomplete?: 'on' | 'off'
}

export interface AInputEmits {
  input: [value: string | null]
}

const props = withDefaults(defineProps<AInputProps>(), {
  type: 'text',
  autocomplete: 'off'
})
const id = computed(() => props.id || useId())
const emit = defineEmits<AInputEmits>()
const model = defineModel<string | null>({ required: true })
const focus = ref<boolean>(false)

const onInput = (e: Event): void => {
  const value = (e.target as HTMLInputElement).value

  model.value = isEmpty(value) ? null : value

  emit('input', model.value)
}
</script>

<style lang="scss">
@use './styles/AInput.styles.scss' as *;
</style>
