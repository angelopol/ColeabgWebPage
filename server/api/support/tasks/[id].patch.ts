import { z } from 'zod'
import { requireSupportAccess } from '~/server/utils/support-access'
import { updateSupportTask } from '~/server/utils/support-tasks'

const bodySchema = z.object({
  title: z.string().trim().min(3).max(220).optional(),
  description: z.string().trim().max(5000).nullable().optional(),
  keywords: z.string().trim().max(400).nullable().optional(),
  module: z.string().trim().max(120).nullable().optional(),
  taskDate: z.string().trim().nullable().optional(),
  status: z.enum(['pendiente', 'finalizada']).optional()
})

export default defineEventHandler(async (event) => {
  requireSupportAccess(event)

  const idRaw = event.context.params?.id
  const id = Number(idRaw)

  if (!idRaw || Number.isNaN(id) || id <= 0) {
    throw createError({
      statusCode: 400,
      statusMessage: 'Identificador de tarea invalido.'
    })
  }

  const body = await readBody(event)
  const parsed = bodySchema.safeParse(body)

  if (!parsed.success) {
    throw createError({
      statusCode: 400,
      statusMessage: 'Datos invalidos para actualizar la tarea.'
    })
  }

  const task = await updateSupportTask(id, {
    title: parsed.data.title,
    description: parsed.data.description,
    keywords: parsed.data.keywords,
    module: parsed.data.module,
    taskDate:
      parsed.data.taskDate === ''
        ? null
        : parsed.data.taskDate === undefined
          ? undefined
          : parsed.data.taskDate,
    status: parsed.data.status
  })

  return {
    ok: true,
    task
  }
})
