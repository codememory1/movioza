export interface BuildOrganizationSchemaInput {
  appUrl: string
  appName: string
}

export const buildOrganizationSchema = (input: BuildOrganizationSchemaInput) => ({
  '@type': 'Organization',
  '@id': `${input.appUrl}#organization`,
  name: input.appName,
  url: input.appUrl,
  logo: {
    '@type': 'ImageObject',
    url: `${input.appUrl}/images/logo.png`
  }
})
