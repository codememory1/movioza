export function toArray(value: unknown) {
  if (!value) {
    return []
  }

  return Array.isArray(value) ? value : [value]
}

export function toNumber(value: unknown): number | undefined {
  const num = Number(value)

  if (Number.isNaN(num)) {
    return undefined
  }

  return num
}
