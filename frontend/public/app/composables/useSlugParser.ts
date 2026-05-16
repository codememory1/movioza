export const useSlugParser = (raw: MaybeRefOrGetter<string>, entity: string) => {
  const match = computed(() => {
    const result = toValue(raw).match(/^(?<id>\d+)-(?<slug>.+)$/)

    if (!result?.groups) {
      throw createError({ statusCode: 404, message: `Invalid ${entity} slug.` })
    }

    return result
  })

  const id = computed<number>(() => Number(match.value.groups!.id))
  const slug = computed<string>(() => String(match.value.groups!.slug))

  return {
    id,
    slug
  }
}
