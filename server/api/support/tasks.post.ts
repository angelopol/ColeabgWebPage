import { z } from 'zod'
import { createSupportTask } from '~/server/utils/support-tasks'
import { requireSupportAccess } from '~/server/utils/support-access'

const STOP_WORDS = new Set([
  'de',
  'la',
  'el',
  'los',
  'las',
  'del',
  'al',
  'y',
  'o',
  'en',
  'por',
  'para',
  'con',
  'sin',
  'una',
  'uno',
  'un'
])

const normalizeToken = (token: string) =>
  token
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-z0-9]/g, '')

const generateKeywordsFromTitle = (title: string) => {
  const tokens = title
    .split(/\s+/)
    .map(normalizeToken)
    .filter((token) => token.length >= 3 && !STOP_WORDS.has(token))

  const unique = [...new Set(tokens)].slice(0, 10)
  return unique.join(', ')
}

const schema = z.object({
  title: z.string().trim().min(3).max(220),
  description: z.string().trim().max(5000).optional().default(''),
  module: z.string().trim().max(120).optional().default(''),
  taskDate: z.string().trim().optional().default(''),
  status: z.enum(['pendiente', 'finalizada']).optional().default('pendiente')
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

  const generatedKeywords = generateKeywordsFromTitle(parsed.data.title)

  const task = await createSupportTask({
    title: parsed.data.title,
    description: parsed.data.description || undefined,
    keywords: generatedKeywords || undefined,
    module: parsed.data.module || undefined,
    taskDate: parsed.data.taskDate || undefined,
    status: parsed.data.status
  })

  return {
    ok: true,
    task
  }
})
