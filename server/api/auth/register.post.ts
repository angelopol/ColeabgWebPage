import { z } from 'zod'
import { sendEmail } from '~/server/utils/mail'
import { hashPassword } from '~/server/utils/password'
import {
  claseExistsInUsers,
  codClieExistsInUsers,
  createUser,
  emailExists,
  findLawyerByCedulaOrId3,
  findLawyerByInpre
} from '~/server/utils/repositories'

const registerSchema = z.object({
  correo: z.string().trim().email(),
  correo2: z.string().trim().email(),
  contra: z.string().min(8),
  contra2: z.string().min(8),
  ci: z.string().trim().min(4),
  ip: z.string().trim().min(3)
})

export default defineEventHandler(async (event) => {
  const body = await readBody(event)
  const parsed = registerSchema.safeParse(body)

  if (!parsed.success) {
    throw createError({
      statusCode: 400,
      statusMessage: 'Datos de registro invalidos o incompletos.'
    })
  }

  const { correo, correo2, contra, contra2, ci, ip } = parsed.data

  if (correo !== correo2) {
    throw createError({ statusCode: 400, statusMessage: 'Los correos no coinciden.' })
  }

  if (contra !== contra2) {
    throw createError({
      statusCode: 400,
      statusMessage: 'Las contrasenas no coinciden.'
    })
  }

  const byCedula = await findLawyerByCedulaOrId3(ci)
  const byInpre = await findLawyerByInpre(ip)

  if (!byCedula || !byInpre || byCedula.CodClie !== byInpre.CodClie) {
    throw createError({
      statusCode: 400,
      statusMessage: 'Cedula e Inpre no coinciden o abogado no inscrito.'
    })
  }

  const [emailTaken, codTaken, claseTaken] = await Promise.all([
    emailExists(correo),
    codClieExistsInUsers(byCedula.CodClie),
    claseExistsInUsers(ip)
  ])

  if (emailTaken || codTaken || claseTaken) {
    throw createError({
      statusCode: 409,
      statusMessage: 'El usuario ya existe en el sistema.'
    })
  }

  const hash = await hashPassword(contra)
  await createUser(correo, hash, byCedula.Clase, byCedula.CodClie)

  await sendEmail(
    correo,
    'Bienvenido al Colegio de Abogados del Estado Carabobo',
    `<h1>Registro completado</h1><p>Usuario: <strong>${correo}</strong></p><p>Tu cuenta fue creada con exito.</p><p>Si no reconoces esta accion, contacta soporte.</p>`
  ).catch(() => null)

  return {
    ok: true,
    message: 'Usuario creado exitosamente.'
  }
})
