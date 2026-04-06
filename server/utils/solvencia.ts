import { createHmac, timingSafeEqual } from 'node:crypto'
import type { H3Event } from 'h3'

const COOKIE_NAME = 'cab_solvencia_access'
const COOKIE_MAX_AGE = 60 * 60 * 24 * 90

type SolvenciaPayload = {
  granted: true
  exp: number
}

const toBase64Url = (value: string) => Buffer.from(value, 'utf8').toString('base64url')
const fromBase64Url = (value: string) => Buffer.from(value, 'base64url').toString('utf8')

const sign = (encodedPayload: string, secret: string) =>
  createHmac('sha256', secret).update(encodedPayload).digest('base64url')

const secureCompare = (left: string, right: string) => {
  const a = Buffer.from(left)
  const b = Buffer.from(right)

  if (a.length !== b.length) {
    return false
  }

  return timingSafeEqual(a, b)
}

const getSolvenciaPassword = () => {
  const config = useRuntimeConfig()
  const password = String(config.solvenciaPassword || '').trim()

  if (!password) {
    throw createError({
      statusCode: 500,
      statusMessage: 'SOLVENCIA_PSSWD no esta configurado en el entorno.'
    })
  }

  return password
}

const getSignSecret = () => {
  const config = useRuntimeConfig()
  return String(config.sessionSecret || 'change-this-secret')
}

const readPayload = (token: string, secret: string): SolvenciaPayload | null => {
  const [encoded, tokenSig] = token.split('.')
  if (!encoded || !tokenSig) {
    return null
  }

  const expectedSig = sign(encoded, secret)
  if (!secureCompare(expectedSig, tokenSig)) {
    return null
  }

  try {
    const payload = JSON.parse(fromBase64Url(encoded)) as SolvenciaPayload
    if (!payload.granted || !payload.exp) {
      return null
    }

    if (payload.exp < Math.floor(Date.now() / 1000)) {
      return null
    }

    return payload
  } catch {
    return null
  }
}

export const verifySolvenciaPassword = (input: string) => {
  const expected = getSolvenciaPassword()
  const provided = String(input || '')

  return secureCompare(expected, provided)
}

export const grantSolvenciaAccess = (event: H3Event) => {
  const exp = Math.floor(Date.now() / 1000) + COOKIE_MAX_AGE
  const payload: SolvenciaPayload = {
    granted: true,
    exp
  }

  const encoded = toBase64Url(JSON.stringify(payload))
  const token = `${encoded}.${sign(encoded, getSignSecret())}`

  setCookie(event, COOKIE_NAME, token, {
    httpOnly: true,
    sameSite: 'lax',
    secure: process.env.NODE_ENV === 'production',
    path: '/',
    maxAge: COOKIE_MAX_AGE
  })
}

export const hasSolvenciaAccess = (event: H3Event) => {
  const token = getCookie(event, COOKIE_NAME)
  if (!token) {
    return false
  }

  return Boolean(readPayload(token, getSignSecret()))
}

export const requireSolvenciaAccess = (event: H3Event) => {
  if (!hasSolvenciaAccess(event)) {
    throw createError({
      statusCode: 401,
      statusMessage: 'Debe ingresar la clave de consulta para acceder a operaciones.'
    })
  }
}
