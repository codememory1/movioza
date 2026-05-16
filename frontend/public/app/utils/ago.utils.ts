export const ago = (date: Date | number): string => {
  const timestamp = date instanceof Date ? Math.floor(date.getTime() / 1000) : date
  const seconds = Math.floor(Date.now() / 1000 - timestamp)
  const minutes = Math.floor(seconds / 60)
  const hours = Math.floor(minutes / 60)
  const days = Math.floor(hours / 24)
  const months = Math.floor(days / 30)
  const years = Math.floor(months / 12)

  if (seconds <= 60) {
    return 'только что'
  }

  if (minutes < 60) {
    return `${minutes} ${plural(minutes, ['минута', 'минуты', 'минут'])} назад`
  }

  if (hours < 24) {
    return `${hours} ${plural(hours, ['час', 'часа', 'часов'])} назад`
  }

  if (days < 30) {
    return `${days} ${plural(days, ['день', 'дня', 'дней'])} назад`
  }

  if (months < 12) {
    return `${months} ${plural(months, ['месяц', 'месяца', 'месяцев'])} назад`
  }

  return `${years} ${plural(years, ['год', 'года', 'лет'])} назад`
}
