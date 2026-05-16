import { MEDIA_TYPES, type MediaType } from '#shared/constants/media-type.constants'
import { MEDIA_KINDS, type MediaKind } from '#shared/constants/media-kind.constants'

export const getAboutMediaLabel = (type: MediaType, kind?: MediaKind | null): string => {
  if (kind && kind in MEDIA_KINDS) {
    return MEDIA_KINDS[kind].aboutLabel
  }

  if (type in MEDIA_TYPES) {
    return MEDIA_TYPES[type].aboutLabel
  }

  return 'Описание'
}
