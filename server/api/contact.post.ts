import { z } from 'zod'
import { sendEmail } from '~/server/utils/mail'

const schema = z.object({
  name: z.string().trim().min(2),
  email: z.string().trim().email(),
  message: z.string().trim().min(10)
})

export default defineEventHandler(async (event) => {
  const body = await readBody(event)
  const parsed = schema.safeParse(body)

  if (!parsed.success) {
    throw createError({ statusCode: 400, statusMessage: 'Formulario invalido.' })
  }

  const config = useRuntimeConfig()
  const to = config.supportEmail || config.smtpUser

  if (!to) {
    throw createError({
      statusCode: 503,
      statusMessage: 'Canal de soporte no configurado.'
    })
  }

  await sendEmail(
    to,
    `Nuevo mensaje de soporte: ${parsed.data.name}`,
    `<h2>Nuevo mensaje de contacto</h2><p><strong>Nombre:</strong> ${parsed.data.name}</p><p><strong>Email:</strong> ${parsed.data.email}</p><p>${parsed.data.message}</p>`
  )

  return {
    ok: true,
    message: 'Mensaje enviado al equipo de soporte.'
  }
})
