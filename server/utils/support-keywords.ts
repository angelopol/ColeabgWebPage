const STOP_WORDS = new Set([
  'de',
  'la',
  'el',
  'los',
  'las',
  'del',
  'al',
  'y',
  'o',
  'en',
  'por',
  'para',
  'con',
  'sin',
  'una',
  'uno',
  'un'
])

const normalizeToken = (token: string) =>
  token
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-z0-9]/g, '')

export const generateKeywordsFromTitle = (title: string) => {
  const tokens = title
    .split(/\s+/)
    .map(normalizeToken)
    .filter((token) => token.length >= 3 && !STOP_WORDS.has(token))

  const unique = [...new Set(tokens)].slice(0, 10)
  return unique.join(', ')
}
