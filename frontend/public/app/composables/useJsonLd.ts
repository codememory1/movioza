import { buildGraphSchema } from '#shared/seo/builders/graph.schema'
import { buildWebsiteSchema } from '#shared/seo/builders/website.schema'
import {
  type BuildBreadcrumbItemSchemaInput,
  buildBreadcrumbListSchema
} from '#shared/seo/builders/breadcrumb.schema'
import { buildOrganizationSchema } from '#shared/seo/builders/organization.schema'

export interface UseJsonLdOptions {
  canonicalUrl: MaybeRefOrGetter<string>
  breadcrumb: MaybeRefOrGetter<BuildBreadcrumbItemSchemaInput[] | false>
  schemas?: MaybeRefOrGetter<object[]>
}

export const useJsonLd = (options: UseJsonLdOptions) => {
  const config = useRuntimeConfig()

  const breadcrumb = computed(() => toValue(options.breadcrumb))
  const resolvedSchemas = computed<object[]>(() => {
    const canonicalUrl = toValue(options.canonicalUrl)
    const defaultSchemas: object[] = [
      buildWebsiteSchema({
        url: config.public.appUrl,
        name: config.public.appShortTitle,
        description: config.public.appDescription
      }),
      buildOrganizationSchema({
        appUrl: config.public.appUrl,
        appName: config.public.appShortTitle
      })
    ]

    if (false !== breadcrumb.value) {
      defaultSchemas.push(
        buildBreadcrumbListSchema({
          appUrl: config.public.appUrl,
          canonicalUrl: canonicalUrl,
          items: [{ name: 'Главная', path: '' }, ...breadcrumb.value]
        })
      )
    }

    return [...defaultSchemas, ...(toValue(options.schemas) ?? [])]
  })

  const graph = computed<object>(() => buildGraphSchema(resolvedSchemas.value))

  useHead(() => ({
    script: [
      {
        type: 'application/ld+json',
        innerHTML: JSON.stringify(graph.value)
      }
    ]
  }))
}
