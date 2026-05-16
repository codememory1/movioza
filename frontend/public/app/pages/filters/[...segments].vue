<template></template>

<script lang="ts" setup>
import { abort404 } from '~/utils/http.utils'

const route = useRoute()
const segments = route.params.segments

if (!Array.isArray(segments)) {
  abort404()
}

if (segments.length % 2 !== 0) {
  abort404()
}
</script>
