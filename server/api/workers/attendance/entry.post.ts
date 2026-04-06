import { workerEntryExists, markWorkerEntry } from '~/server/utils/repositories'
import { requireSession } from '~/server/utils/session'

export default defineEventHandler(async (event) => {
  const session = requireSession(event)

  if (!['worker', 'admin'].includes(session.role)) {
    throw createError({ statusCode: 403, statusMessage: 'No autorizado.' })
  }

  const now = new Intl.DateTimeFormat('en-CA', {
    timeZone: 'America/Caracas',
    year: 'numeric',
    month: '2-digit',
    day: '2-digit'
  }).format(new Date())

  const exists = await workerEntryExists(session.email, now)

  if (exists) {
    throw createError({
      statusCode: 409,
      statusMessage: 'Ya se registro una entrada para hoy.'
    })
  }

  const timestamp = await markWorkerEntry(session.email)

  return {
    ok: true,
    timestamp
  }
})
