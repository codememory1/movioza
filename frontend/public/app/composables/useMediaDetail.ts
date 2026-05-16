import { mediaService } from '#shared/services/media.service'

export const useMediaDetail = (id: MaybeRefOrGetter<number>) => {
  const resolvedId = computed(() => toValue(id))

  return useAsyncData(`media-detail-${id}`, () => mediaService.getDetailById(resolvedId.value))
}
