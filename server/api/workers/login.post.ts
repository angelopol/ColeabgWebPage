import { z } from 'zod'
import { findWorkerByEmail } from '~/server/utils/repositories'
import { setUserSession } from '~/server/utils/session'

const schema = z.object({
  user: z.string().trim().email()
})

export default defineEventHandler(async (event) => {
  const body = await readBody(event)
  const parsed = schema.safeParse(body)

  if (!parsed.success) {
    throw createError({
      statusCode: 400,
      statusMessage: 'Debe indicar un correo de trabajador valido.'
    })
  }

  const worker = await findWorkerByEmail(parsed.data.user)
  if (!worker) {
    throw createError({
      statusCode: 401,
      statusMessage: 'No autorizado para registrar asistencia.'
    })
  }

  const session = {
    email: worker.email,
    role: 'worker' as const,
    codClie: null,
    clase: null,
    displayName: worker.email
  }

  setUserSession(event, session)

  return {
    ok: true,
    user: session
  }
})
