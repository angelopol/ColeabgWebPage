import { createHmac, timingSafeEqual } from 'node:crypto'
import type { H3Event } from 'h3'
import type { UserSession } from '~/types/domain'

const COOKIE_NAME = 'cab_session'
const COOKIE_MAX_AGE = 60 * 60 * 8

interface SignedPayload extends UserSession {
  exp: number
}

const encode = (value: string) => Buffer.from(value, 'utf8').toString('base64url')
const decode = (value: string) => Buffer.from(value, 'base64url').toString('utf8')

const signature = (encodedPayload: string, secret: string) =>
  createHmac('sha256', secret).update(encodedPayload).digest('base64url')

const parseSecret = () => {
  const config = useRuntimeConfig()
  return config.sessionSecret || 'change-this-secret'
}

const safeCompare = (left: string, right: string) => {
  const a = Buffer.from(left)
  const b = Buffer.from(right)

  if (a.length !== b.length) {
    return false
  }

  return timingSafeEqual(a, b)
}

const createToken = (payload: SignedPayload, secret: string) => {
  const encoded = encode(JSON.stringify(payload))
  return `${encoded}.${signature(encoded, secret)}`
}

const readToken = (token: string, secret: string): SignedPayload | null => {
  const [encoded, sig] = token.split('.')
  if (!encoded || !sig) {
    return null
  }

  const expected = signature(encoded, secret)
  if (!safeCompare(sig, expected)) {
    return null
  }

  try {
    const payload = JSON.parse(decode(encoded)) as SignedPayload
    if (!payload.exp || payload.exp < Math.floor(Date.now() / 1000)) {
      return null
    }

    return payload
  } catch {
    return null
  }
}

export const setUserSession = (event: H3Event, user: UserSession) => {
  const payload: SignedPayload = {
    ...user,
    exp: Math.floor(Date.now() / 1000) + COOKIE_MAX_AGE
  }

  const token = createToken(payload, parseSecret())

  setCookie(event, COOKIE_NAME, token, {
    httpOnly: true,
    sameSite: 'lax',
    secure: process.env.NODE_ENV === 'production',
    path: '/',
    maxAge: COOKIE_MAX_AGE
  })
}

export const clearUserSession = (event: H3Event) => {
  deleteCookie(event, COOKIE_NAME, { path: '/' })
}

export const getUserSession = (event: H3Event): UserSession | null => {
  const token = getCookie(event, COOKIE_NAME)
  if (!token) {
    return null
  }

  const payload = readToken(token, parseSecret())
  if (!payload) {
    return null
  }

  return {
    email: payload.email,
    role: payload.role,
    codClie: payload.codClie ?? null,
    clase: payload.clase ?? null,
    displayName: payload.displayName ?? null
  }
}

export const requireSession = (event: H3Event) => {
  const session = getUserSession(event)
  if (!session) {
    throw createError({ statusCode: 401, statusMessage: 'No autenticado' })
  }

  return session
}

export const requireAdminSession = (event: H3Event) => {
  const session = requireSession(event)

  if (session.role !== 'admin') {
    throw createError({ statusCode: 403, statusMessage: 'No autorizado' })
  }

  return session
}
