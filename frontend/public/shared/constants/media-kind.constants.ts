export interface MediaKindConfig {
  readonly aboutLabel: string
}

export const MEDIA_KINDS = {
  animation: {
    aboutLabel: 'О мультфильме'
  }
} as const satisfies Record<string, MediaKindConfig>

export type MediaKind = keyof typeof MEDIA_KINDS
