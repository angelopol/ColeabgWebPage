import { z } from 'zod'
import { sendEmail } from '~/server/utils/mail'
import { hashPassword, verifyPassword } from '~/server/utils/password'
import { findUserByEmail, updateUserPasswordByEmail } from '~/server/utils/repositories'
import { requireSession } from '~/server/utils/session'

const schema = z.object({
  contra: z.string().min(8),
  contra2: z.string().min(8),
  contranew: z.string().min(8),
  contranew2: z.string().min(8)
})

export default defineEventHandler(async (event) => {
  const session = requireSession(event)
  const body = await readBody(event)
  const parsed = schema.safeParse(body)

  if (!parsed.success) {
    throw createError({ statusCode: 400, statusMessage: 'Datos invalidos.' })
  }

  const { contra, contra2, contranew, contranew2 } = parsed.data

  if (contra !== contra2) {
    throw createError({
      statusCode: 400,
      statusMessage: 'La contrasena actual no coincide con su confirmacion.'
    })
  }

  if (contranew !== contranew2) {
    throw createError({
      statusCode: 400,
      statusMessage: 'La contrasena nueva no coincide con su confirmacion.'
    })
  }

  const account = await findUserByEmail(session.email)
  if (!account || !account.pass) {
    throw createError({ statusCode: 404, statusMessage: 'Usuario no encontrado.' })
  }

  const validCurrent = await verifyPassword(contra, account.pass)
  if (!validCurrent) {
    throw createError({
      statusCode: 401,
      statusMessage: 'La contrasena actual es incorrecta.'
    })
  }

  const hash = await hashPassword(contranew)
  await updateUserPasswordByEmail(session.email, hash)

  await sendEmail(
    session.email,
    'Contrasena actualizada',
    '<h2>Su contrasena fue actualizada.</h2><p>Si no reconoce esta accion, contacte soporte de inmediato.</p>'
  ).catch(() => null)

  return {
    ok: true,
    message: 'Contrasena actualizada.'
  }
})
