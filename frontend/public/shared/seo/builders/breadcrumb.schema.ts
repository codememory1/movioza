export interface BuildBreadcrumbListSchemaInput {
  appUrl: string
  canonicalUrl: string
  items: BuildBreadcrumbItemSchemaInput[]
}

export interface BuildBreadcrumbItemSchemaInput {
  name: string
  path: string
  hidden?: boolean
}

export const buildBreadcrumbListSchema = (input: BuildBreadcrumbListSchemaInput) => ({
  '@id': `${input.canonicalUrl}#breadcrumb`,
  '@type': 'BreadcrumbList',
  itemListElement: input.items
    .filter((item) => !item.hidden)
    .map((item, index) => ({
      '@type': 'ListItem',
      position: index + 1,
      name: item.name,
      item: `${input.appUrl}${item.path}`
    }))
})
