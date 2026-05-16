import type { ImageModel } from '#shared/types/common'

export interface PersonShortModel {
  id: number
  slug: string
  name: string
}

export interface PersonModel extends PersonShortModel {
  photo: ImageModel | null
}
