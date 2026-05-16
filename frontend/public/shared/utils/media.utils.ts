import type { MediaType } from '#shared/constants/media-type.constants'
import type { MediaKind } from '#shared/constants/media-kind.constants'

export function isMovie(type: MediaType): boolean {
  return type === 'movie'
}

export function isSeries(type: MediaType): boolean {
  return type === 'series'
}

export function isAnimation(kind?: MediaKind): boolean {
  return kind === 'animation'
}

export function isAnimationSeries(type: MediaType, kind?: MediaKind): boolean {
  return isSeries(type) && isAnimation(kind)
}

export function isAnimationMovie(type: MediaType, kind?: MediaKind): boolean {
  return isMovie(type) && isAnimation(kind)
}

export function isFamilyFriendly(kind?: MediaKind, ageRating?: number): boolean {
  if (isAnimation(kind)) {
    return true
  }

  return !!(ageRating && ageRating <= 6)
}
