import qs from 'qs'

export const withQuery = (url: string, queryParams: object): string => {
  const query = qs.stringify(queryParams, {
    skipNulls: true,
    strictNullHandling: true
  })

  if (!query) {
    return url
  }

  return `${url}?${query}`
}
