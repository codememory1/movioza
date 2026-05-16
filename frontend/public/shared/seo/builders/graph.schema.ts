export const buildGraphSchema = (nodes: object[]) => ({
  '@context': 'https://schema.org',
  '@graph': nodes
})
