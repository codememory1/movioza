export interface MediaTypeConfig {
  readonly apiType: string
  readonly aboutLabel: string
}

export const MEDIA_TYPES = {
  movie: {
    apiType: 'movie',
    aboutLabel: 'О фильме'
  },
  series: {
    apiType: 'series',
    aboutLabel: 'О сериале'
  }
} as const satisfies Record<string, MediaTypeConfig>

export type MediaType = keyof typeof MEDIA_TYPES
