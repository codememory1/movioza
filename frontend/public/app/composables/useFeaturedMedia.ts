import { mediaService } from '#shared/services/media.service'

export const useFeaturedMedia = () => {
  return useAsyncData('featured-media', () => mediaService.getFeatured())
}
