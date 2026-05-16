export interface BuildWebsiteSchemaInput {
  url: string
  name: string
  description: string
}

export const buildWebsiteSchema = (input: BuildWebsiteSchemaInput) => ({
  '@id': `${input.url}#website`,
  '@type': 'WebSite',
  name: input.name,
  description: input.description,
  url: input.url,
  inLanguage: 'ru',
  potentialAction: {
    '@type': 'SearchAction',
    target: {
      '@type': 'EntryPoint',
      urlTemplate: `${input.url}/search?q={search_term_string}`
    },
    'query-input': 'required name=search_term_string'
  },
  publisher: {
    '@id': `${input.url}#organization`
  }
})
