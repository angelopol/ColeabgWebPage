import { z } from 'zod'
import {
  grantSolvenciaAccess,
  verifySolvenciaPassword
} from '~/server/utils/solvencia'

const schema = z.object({
  password: z.string().min(1)
})

export default defineEventHandler(async (event) => {
  const body = await readBody(event)
  const parsed = schema.safeParse(body)

  if (!parsed.success) {
    throw createError({
      statusCode: 400,
      statusMessage: 'Debe indicar la clave de consulta.'
    })
  }

  const valid = verifySolvenciaPassword(parsed.data.password)
  if (!valid) {
    throw createError({
      statusCode: 401,
      statusMessage: 'Clave de consulta incorrecta.'
    })
  }

  grantSolvenciaAccess(event)

  return {
    ok: true,
    granted: true,
    expiresInDays: 90
  }
})
