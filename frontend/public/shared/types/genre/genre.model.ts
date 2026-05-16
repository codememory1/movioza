export interface GenreShortModel {
  id: number
  slug: string
  name: string
}

export interface GenreModel extends GenreShortModel {
  subtitle?: string
  emoji: string
}
