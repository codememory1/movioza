<template>
  <div
    class="a-textarea"
    :class="{
      [`a-textarea-resize--${resize}`]: true,
      'a-textarea--focus': focus
    }"
  >
    <textarea
      :name="name"
      :placeholder="placeholder"
      :value="model"
      :rows="rows"
      class="a-textarea__textarea"
      @click="onInput"
      @focus="focus = true"
      @blur="focus = false"
    />
    <slot name="afterTextarea" />
  </div>
</template>

<script lang="ts" setup>
import { isEmpty } from 'lodash'

export interface ATextareaProps {
  name: string
  placeholder: string
  resize?: 'none' | 'horizontal' | 'vertical'
  rows?: number
}

export interface ATextareaEmits {
  input: [value: string | null]
}

withDefaults(defineProps<ATextareaProps>(), {
  resize: 'none'
})

const emit = defineEmits<ATextareaEmits>()
const model = defineModel<string | null>({ required: true })
const focus = ref<boolean>(false)

const onInput = (e: Event): void => {
  const value = (e.target as HTMLInputElement).value

  model.value = isEmpty(value) ? null : value

  emit('input', model.value)
}
</script>

<style lang="scss">
@use './styles/ATextarea.styles' as *;
</style>
