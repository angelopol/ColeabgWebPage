import { z } from 'zod'
import { requireSupportAccess } from '~/server/utils/support-access'
import { listCompletedTasksForReport } from '~/server/utils/support-tasks'

const dateRegex = /^\d{4}-\d{2}-\d{2}$/

const schema = z.object({
  fromDate: z.string().trim().regex(dateRegex),
  toDate: z.string().trim().regex(dateRegex),
  keyword: z.string().trim().max(220).optional().default(''),
  deParte: z.string().trim().min(2).max(200),
  para: z.string().trim().min(2).max(200)
})

export default defineEventHandler(async (event) => {
  requireSupportAccess(event)

  const body = await readBody(event)
  const parsed = schema.safeParse(body)

  if (!parsed.success) {
    throw createError({
      statusCode: 400,
      statusMessage: 'Datos invalidos para generar el reporte.'
    })
  }

  const fromDate = parsed.data.fromDate
  const toDate = parsed.data.toDate

  if (fromDate > toDate) {
    throw createError({
      statusCode: 400,
      statusMessage: 'El rango de fechas es invalido.'
    })
  }

  const tasks = await listCompletedTasksForReport(
    fromDate,
    toDate,
    parsed.data.keyword || undefined
  )

  return {
    report: {
      generatedAt: new Date().toISOString(),
      fromDate,
      toDate,
      keyword: parsed.data.keyword || null,
      deParte: parsed.data.deParte,
      para: parsed.data.para,
      totalTasks: tasks.length,
      tasks
    }
  }
})
