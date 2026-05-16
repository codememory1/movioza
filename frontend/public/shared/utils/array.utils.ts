export const toggleArrayValue = <T>(items: T[], value: T): void => {
  const index = items.indexOf(value)

  if (index === -1) {
    items.push(value)
    return
  }

  items.splice(index, 1)
}
