const twoDigits = (value: number) => String(value).padStart(2, '0')

export const nowInCaracas = () => {
  const now = new Date()
  const parts = new Intl.DateTimeFormat('en-GB', {
    timeZone: 'America/Caracas',
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: false
  })
    .formatToParts(now)
    .reduce<Record<string, string>>((acc, part) => {
      if (part.type !== 'literal') {
        acc[part.type] = part.value
      }
      return acc
    }, {})

  const yyyy = parts.year
  const mm = parts.month
  const dd = parts.day
  const hh = parts.hour ?? twoDigits(now.getHours())
  const mi = parts.minute ?? twoDigits(now.getMinutes())
  const ss = parts.second ?? twoDigits(now.getSeconds())

  return {
    date: `${yyyy}-${mm}-${dd}`,
    datetime: `${yyyy}-${mm}-${dd} ${hh}:${mi}:${ss}`
  }
}
