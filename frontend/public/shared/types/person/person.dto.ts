import type { ImageDto } from '#shared/types/common'

export interface PersonShortDto {
  id: number
  slug: string
  name: string
}

export interface PersonDto extends PersonShortDto {
  photo: ImageDto | null
}
