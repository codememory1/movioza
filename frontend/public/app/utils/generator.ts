export const generateGradientFromSlug = (slug: string) => {
  let hash = 0

  for (let i = 0; i < slug.length; i++) {
    hash = slug.charCodeAt(i) + ((hash << 5) - hash)
  }

  const hue = Math.abs(hash) % 360

  return {
    from: `hsl(${hue}, 65%, 42%)`,
    to: `hsl(${(hue + 40) % 360}, 65%, 32%)`
  }
}
