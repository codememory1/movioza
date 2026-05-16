export function secondsToIsoString(seconds: number): string {
  const hours = Math.floor(seconds / 3600)
  const minutes = Math.floor((seconds % 3600) / 60)
  const secs = seconds % 60

  let result = 'PT'

  if (hours) {
    result += `${hours}H`
  }

  if (minutes) {
    result += `${minutes}M`
  }

  if (secs) {
    result += `${secs}S`
  }

  return result
}

export function toSchemaDateTime(date: Date): string | undefined {
  return date.toISOString().split('T')[0]
}
