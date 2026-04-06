import { z } from 'zod'
import { assistsInRange } from '~/server/utils/repositories'
import { requireAdminSession } from '~/server/utils/session'

const dateSchema = z
  .string()
  .regex(/^\d{4}-\d{2}-\d{2}$/)

export default defineEventHandler(async (event) => {
  requireAdminSession(event)

  const query = getQuery(event)
  const fromDate = String(query.from || '')
  const toDate = String(query.to || '')

  if (!dateSchema.safeParse(fromDate).success || !dateSchema.safeParse(toDate).success) {
    throw createError({
      statusCode: 400,
      statusMessage: 'Parametros from y to deben tener formato YYYY-MM-DD.'
    })
  }

  const rows = await assistsInRange(fromDate, toDate)

  return {
    rows
  }
})
