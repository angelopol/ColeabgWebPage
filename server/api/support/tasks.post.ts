import { z } from 'zod'
import { createSupportTask } from '~/server/utils/support-tasks'
import { requireSupportAccess } from '~/server/utils/support-access'

const schema = z.object({
  title: z.string().trim().min(3).max(220),
  description: z.string().trim().max(5000).optional().default(''),
  keywords: z.string().trim().max(400).optional().default(''),
  module: z.string().trim().max(120).optional().default(''),
  taskDate: z.string().trim().optional().default('')
})

export default defineEventHandler(async (event) => {
  requireSupportAccess(event)

  const body = await readBody(event)
  const parsed = schema.safeParse(body)

  if (!parsed.success) {
    throw createError({
      statusCode: 400,
      statusMessage: 'Datos invalidos para crear tarea.'
    })
  }

  const task = await createSupportTask({
    title: parsed.data.title,
    description: parsed.data.description || undefined,
    keywords: parsed.data.keywords || undefined,
    module: parsed.data.module || undefined,
    taskDate: parsed.data.taskDate || undefined
  })

  return {
    ok: true,
    task
  }
})
