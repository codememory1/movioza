import type { PersonModel } from '#shared/types/person'

export interface BuildPersonSchemaInput {
  appUrl: string
  person: PersonModel
  profilePath: string
}

export const buildPersonSchema = (input: BuildPersonSchemaInput) => ({
  '@type': 'Person',
  '@id': `${input.appUrl}${input.profilePath}#person`,
  name: input.person.name,
  url: `${input.appUrl}${input.profilePath}`,
  ...(input.person.photo?.url && {
    image: input.person.photo.url
  })
})
