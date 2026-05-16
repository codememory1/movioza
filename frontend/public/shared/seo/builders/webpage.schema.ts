export type BuildWebPageSchemaType = 'WebPage' | 'CollectionPage' | 'ItemPage'
export type BuildWebPageSchemaAction = 'ReadAction'

export interface BuildWebPageSchemaInput {
  type?: BuildWebPageSchemaType
  appUrl: string
  canonicalUrl: string
  title: string
  description: string
  action?: {
    type: BuildWebPageSchemaAction
    target?: string[]
  }
  mainEntity?: string
  breadcrumbHidden?: boolean
}

export const buildWebPageSchema = (input: BuildWebPageSchemaInput) => ({
  '@id': `${input.canonicalUrl}#webpage`,
  '@type': input.type ? input.type : 'WebPage',
  name: input.title,
  description: input.description,
  url: input.canonicalUrl,
  inLanguage: 'ru',
  ...(!input.breadcrumbHidden && {
    breadcrumb: {
      '@id': `${input.canonicalUrl}#breadcrumb`
    }
  }),
  isPartOf: {
    '@id': `${input.appUrl}#website`
  },
  ...(input.mainEntity && {
    mainEntity: {
      '@id': input.mainEntity
    }
  }),
  ...(input.action && {
    potentialAction: {
      '@type': input.action.type,
      target: [input.canonicalUrl, ...(input.action.target || [])]
    }
  }),
  publisher: {
    '@id': `${input.appUrl}#organization`
  }
})
