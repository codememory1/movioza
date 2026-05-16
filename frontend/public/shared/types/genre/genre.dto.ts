export interface GenreShortDto {
  id: number
  slug: string
  name: string
}

export interface GenreDto extends GenreShortDto {
  subtitle?: string
  emoji: string
}
