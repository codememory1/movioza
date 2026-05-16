<template>
  <NuxtLink class="actor-tag" :to="`/actor/${actor.slug}`">
    <slot>{{ actor.name }}</slot>
  </NuxtLink>
</template>

<script lang="ts" setup>
import type { PersonShortModel } from '#shared/types/person'

export interface ActorTagProps {
  actor: PersonShortModel
}

defineProps<ActorTagProps>()
</script>
