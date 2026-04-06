import { z } from 'zod'
import {
  createSolvency,
  hasActiveCarnet,
  hasActiveSolvencyByCodClie
} from '~/server/utils/repositories'
import { requireAdminSession } from '~/server/utils/session'

const schema = z.object({
  ci: z.string().trim().min(4),
  hasta: z.string().trim().min(4),
  NumeroD: z.string().trim().min(1),
  CarnetNum: z.string().trim().optional().default(''),
  CarnetNum2: z.string().trim().optional().default('')
})

export default defineEventHandler(async (event) => {
  requireAdminSession(event)
  const body = await readBody(event)
  const parsed = schema.safeParse(body)

  if (!parsed.success) {
    throw createError({ statusCode: 400, statusMessage: 'Datos invalidos.' })
  }

  const { ci, hasta, NumeroD, CarnetNum, CarnetNum2 } = parsed.data

  if (CarnetNum && CarnetNum !== CarnetNum2) {
    throw createError({
      statusCode: 400,
      statusMessage: 'El numero de carnet no coincide con su confirmacion.'
    })
  }

  const exists = await hasActiveSolvencyByCodClie(ci)
  if (exists) {
    throw createError({
      statusCode: 409,
      statusMessage: 'Ya existe una solvencia activa para esta cedula.'
    })
  }

  if (CarnetNum) {
    const duplicateCarnet = await hasActiveCarnet(CarnetNum)
    if (duplicateCarnet) {
      throw createError({
        statusCode: 409,
        statusMessage: 'Ya existe una persona con ese carnet activo.'
      })
    }
  }

  await createSolvency({
    codClie: ci,
    hasta,
    numeroD: NumeroD,
    carnetNum2: CarnetNum || null
  })

  return {
    ok: true,
    message: 'Solvencia registrada correctamente.'
  }
})
