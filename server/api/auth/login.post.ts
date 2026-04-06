import { z } from 'zod'
import { detectRole } from '~/server/utils/normalizers'
import {
  findLawyerByCedulaOrId3,
  findUserByEmail,
  updateUserPasswordByEmail
} from '~/server/utils/repositories'
import { hashPassword, isBcryptHash, verifyPassword } from '~/server/utils/password'
import { setUserSession } from '~/server/utils/session'

const loginSchema = z.object({
  user: z.string().trim().min(3),
  password: z.string().min(1)
})

export default defineEventHandler(async (event) => {
  const body = await readBody(event)
  const parsed = loginSchema.safeParse(body)

  if (!parsed.success) {
    throw createError({ statusCode: 400, statusMessage: 'Datos incompletos.' })
  }

  const { user, password } = parsed.data
  const account = await findUserByEmail(user)

  if (!account || !account.pass) {
    throw createError({ statusCode: 401, statusMessage: 'Credenciales invalidas.' })
  }

  const valid = await verifyPassword(password, account.pass)
  if (!valid) {
    throw createError({ statusCode: 401, statusMessage: 'Credenciales invalidas.' })
  }

  if (!isBcryptHash(account.pass)) {
    const upgradedHash = await hashPassword(password)
    await updateUserPasswordByEmail(account.email, upgradedHash)
  }

  const profile = account.CodClie
    ? await findLawyerByCedulaOrId3(account.CodClie)
    : null

  const session = {
    email: account.email,
    role: detectRole(account.email),
    codClie: account.CodClie,
    clase: account.Clase,
    displayName: profile?.Descrip ?? account.email
  } as const

  setUserSession(event, session)

  return {
    ok: true,
    user: session
  }
})
