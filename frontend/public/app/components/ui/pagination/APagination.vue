<template>
  <nav class="a-pagination" aria-label="Пагинация">
    <ul class="a-pagination__list">
      <li>
        <button v-if="isFirst" type="button" class="a-pagination__item" disabled>
          <AChevronLeftIcon />
        </button>
        <NuxtLink
          v-else
          class="a-pagination__item"
          aria-label="Предыдущая страница"
          :to="link(model - 1)"
        >
          <AChevronLeftIcon />
        </NuxtLink>
      </li>
      <li>
        <NuxtLink
          class="a-pagination__item"
          :class="{ 'a-pagination__item--selected': model === 1 }"
          aria-label="Страница 1"
          :to="link(1)"
        >
          1
        </NuxtLink>
      </li>
      <li v-if="left > 2">
        <span class="a-pagination__dots">...</span>
      </li>
      <li v-for="page in range" :key="page">
        <NuxtLink
          class="a-pagination__item"
          :class="{ 'a-pagination__item--selected': model === page }"
          :aria-label="`Страница ${page}`"
          :to="link(page)"
        >
          {{ page }}
        </NuxtLink>
      </li>
      <li v-if="right < total - 1">
        <span class="a-pagination__dots">...</span>
      </li>
      <li v-if="total > 1">
        <NuxtLink
          class="a-pagination__item dots"
          :class="{ 'a-pagination__item--selected': model === total }"
          :aria-label="`Страница ${total}`"
          :to="link(total)"
        >
          {{ total }}
        </NuxtLink>
      </li>
      <li>
        <button v-if="isLast" disabled type="button" class="a-pagination__item">
          <AChevronRightIcon />
        </button>
        <NuxtLink
          v-else
          class="a-pagination__item"
          aria-label="Следующая страница"
          :to="link(model + 1)"
        >
          <AChevronRightIcon />
        </NuxtLink>
      </li>
    </ul>
  </nav>
</template>

<script lang="ts" setup>
import AChevronLeftIcon from '~/components/ui/icon/AChevronLeftIcon.vue'
import AChevronRightIcon from '~/components/ui/icon/AChevronRightIcon.vue'
import type { RouteLocationRaw } from 'vue-router'

export interface APaginationProps {
  total: number
  delta?: number
  link: (page: number) => RouteLocationRaw
}

const props = withDefaults(defineProps<APaginationProps>(), {
  delta: 2
})
const model = defineModel<number>({ required: true })
const left = computed<number>(() => Math.max(2, model.value - props.delta))
const right = computed<number>(() => Math.min(props.total - 1, model.value + props.delta))
const isFirst = computed<boolean>(() => model.value === 1)
const isLast = computed<boolean>(() => model.value === props.total)
const range = computed<number[]>(() => {
  const result: number[] = []

  for (let i = left.value; i <= right.value; i++) {
    result.push(i)
  }

  return result
})
</script>

<style lang="scss">
@use './styles/APagination.styles' as *;
</style>
