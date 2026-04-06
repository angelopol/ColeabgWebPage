import { z } from 'zod'
import {
  grantSupportAccess,
  verifySupportPassword
} from '~/server/utils/support-access'

const schema = z.object({
  password: z.string().min(1)
})

export default defineEventHandler(async (event) => {
  const body = await readBody(event)
  const parsed = schema.safeParse(body)

  if (!parsed.success) {
    throw createError({
      statusCode: 400,
      statusMessage: 'Debe indicar la clave de soporte.'
    })
  }

  const valid = verifySupportPassword(parsed.data.password)
  if (!valid) {
    throw createError({
      statusCode: 401,
      statusMessage: 'Clave de soporte incorrecta.'
    })
  }

  grantSupportAccess(event)

  return {
    ok: true,
    granted: true,
    expiresInDays: 90
  }
})
