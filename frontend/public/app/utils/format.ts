const intlNumberFormat = new Intl.NumberFormat('ru-RU')

export const formatDuration = (seconds: number): string => {
  if (!seconds || seconds < 0) {
    return '0 мин'
  }

  const hours = Math.floor(seconds / 3600)
  const minutes = Math.floor((seconds % 3600) / 60)

  if (hours > 0) {
    return `${hours} ч ${minutes} мин`
  }

  return `${minutes} мин`
}

export const formatHMS = (seconds: number): string => {
  if (!Number.isFinite(seconds) || seconds < 0) {
    return '00:00'
  }

  const total = Math.floor(seconds)
  const hours = Math.floor(total / 3600)
  const minutes = Math.floor((total % 3600) / 60)
  const secs = total % 60

  const mm = String(minutes).padStart(2, '0')
  const ss = String(secs).padStart(2, '0')

  if (hours > 0) {
    const hh = String(hours).padStart(2, '0')

    return `${hh}:${mm}:${ss}`
  }

  return `${mm}:${ss}`
}

export const formatNumber = (num: number): string => intlNumberFormat.format(num)
