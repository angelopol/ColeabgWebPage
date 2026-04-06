import { z } from 'zod'
import { markNumeroDSolved } from '~/server/utils/repositories'
import { requireAdminSession } from '~/server/utils/session'

const schema = z.object({
  numeroD: z.string().trim().min(1)
})

export default defineEventHandler(async (event) => {
  requireAdminSession(event)
  const body = await readBody(event)
  const parsed = schema.safeParse(body)

  if (!parsed.success) {
    throw createError({ statusCode: 400, statusMessage: 'NumeroD invalido.' })
  }

  await markNumeroDSolved(parsed.data.numeroD)

  return {
    ok: true
  }
})
