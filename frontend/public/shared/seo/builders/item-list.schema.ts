export interface BuildItemListSchemaInput {
  canonicalUrl: string
  id?: string
  name?: string
  page?: number
  limit?: number
  items: BuildListItemSchemaInput[]
}

export interface BuildListItemSchemaInput {
  url: string
  item: Record<string, any>
}

export const buildItemListSchema = (input: BuildItemListSchemaInput) => {
  const page = input.page ?? 1
  const limit = input.limit ?? input.items.length

  return {
    '@id': input.id ? input.id : `${input.canonicalUrl}#itemlist`,
    '@type': 'ItemList',
    ...(input.name && {
      name: input.name
    }),
    numberOfItems: input.items.length,
    itemListElement: input.items.map((item, index) => ({
      '@type': 'ListItem',
      position: (page - 1) * limit + index + 1,
      item: item.item
    }))
  }
}
