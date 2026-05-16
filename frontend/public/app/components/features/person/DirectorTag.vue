<template>
  <NuxtLink class="a-director-tag" :to="`/director/${director.slug}`">
    <slot>{{ director.name }}</slot>
  </NuxtLink>
</template>

<script lang="ts" setup>
import type { PersonShortModel } from '#shared/types/person'

export interface DirectorTagProps {
  director: PersonShortModel
}

defineProps<DirectorTagProps>()
</script>
