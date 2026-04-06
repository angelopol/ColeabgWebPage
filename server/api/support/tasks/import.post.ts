import { z } from 'zod'
import { requireSupportAccess } from '~/server/utils/support-access'
import { createSupportTask } from '~/server/utils/support-tasks'
import { generateKeywordsFromTitle } from '~/server/utils/support-keywords'

const schema = z.object({
  entriesText: z.string().trim().min(1),
  module: z.string().trim().max(120).optional().default('')
})

const isLeapYear = (year: number) =>
  (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0

const daysInMonth = (year: number, month: number) => {
  if ([1, 3, 5, 7, 8, 10, 12].includes(month)) {
    return 31
  }

  if ([4, 6, 9, 11].includes(month)) {
    return 30
  }

  return isLeapYear(year) ? 29 : 28
}

const toIsoDate = (year: number, month: number, day: number) => {
  if (year < 1900 || year > 2200) {
    return null
  }

  if (month < 1 || month > 12) {
    return null
  }

  const maxDay = daysInMonth(year, month)
  if (day < 1 || day > maxDay) {
    return null
  }

  const yyyy = String(year).padStart(4, '0')
  const mm = String(month).padStart(2, '0')
  const dd = String(day).padStart(2, '0')

  return `${yyyy}-${mm}-${dd}`
}

const parseInputDate = (rawDate: string) => {
  const value = rawDate.trim()

  const isoMatch = value.match(/^(\d{4})-(\d{2})-(\d{2})$/)
  if (isoMatch) {
    return toIsoDate(Number(isoMatch[1]), Number(isoMatch[2]), Number(isoMatch[3]))
  }

  const slashMatch = value.match(/^(\d{2})\/(\d{2})\/(\d{4})$/)
  if (slashMatch) {
    return toIsoDate(Number(slashMatch[3]), Number(slashMatch[2]), Number(slashMatch[1]))
  }

  const dashMatch = value.match(/^(\d{2})-(\d{2})-(\d{4})$/)
  if (dashMatch) {
    return toIsoDate(Number(dashMatch[3]), Number(dashMatch[2]), Number(dashMatch[1]))
  }

  return null
}

export default defineEventHandler(async (event) => {
  requireSupportAccess(event)

  const body = await readBody(event)
  const parsed = schema.safeParse(body)

  if (!parsed.success) {
    throw createError({
      statusCode: 400,
      statusMessage: 'Debe indicar el listado de actividades para importar.'
    })
  }

  const lines = parsed.data.entriesText
    .split(/\r?\n/)
    .map((line) => line.trim())
    .filter(Boolean)

  if (!lines.length) {
    throw createError({
      statusCode: 400,
      statusMessage: 'El listado esta vacio.'
    })
  }

  const imported = [] as Array<{ title: string; taskDate: string }>

  for (let index = 0; index < lines.length; index += 1) {
    const line = lines[index]
    const separator = line.indexOf(';')

    if (separator <= 0 || separator >= line.length - 1) {
      throw createError({
        statusCode: 400,
        statusMessage: `Formato invalido en la linea ${index + 1}. Use {titulo};{fecha}.`
      })
    }

    const title = line.slice(0, separator).trim()
    const rawDate = line.slice(separator + 1).trim()

    if (!title || title.length < 3 || title.length > 220) {
      throw createError({
        statusCode: 400,
        statusMessage: `Titulo invalido en la linea ${index + 1}.`
      })
    }

    const taskDate = parseInputDate(rawDate)
    if (!taskDate) {
      throw createError({
        statusCode: 400,
        statusMessage: `Fecha invalida en la linea ${index + 1}. Use YYYY-MM-DD o DD/MM/YYYY.`
      })
    }

    const keywords = generateKeywordsFromTitle(title)

    await createSupportTask({
      title,
      module: parsed.data.module || undefined,
      taskDate,
      keywords: keywords || undefined,
      status: 'finalizada',
      completedAt: `${taskDate}T00:00:00`
    })

    imported.push({
      title,
      taskDate
    })
  }

  return {
    ok: true,
    importedCount: imported.length,
    imported
  }
})
