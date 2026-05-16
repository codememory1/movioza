import { personService } from '#shared/services/person.service'
import type { MediaShortModel } from '#shared/types/media'

export const usePersonMediaList = async (personId: MaybeRefOrGetter<number>) => {
  const resolvedPersonId = computed(() => toValue(personId))
  const { data, error, pending } = await useAsyncData(
    `person-${resolvedPersonId.value}-media`,
    () => personService.getMedia(resolvedPersonId.value),
    {
      watch: [resolvedPersonId]
    }
  )
  const items = ref<MediaShortModel[]>(data.value?.data || [])

  watch(data, () => {
    items.value = data.value?.data || []
  })

  return {
    error,
    pending,
    items
  }
}
