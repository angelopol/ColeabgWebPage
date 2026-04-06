import { workerEntryExists, markWorkerExit } from '~/server/utils/repositories'
import { requireSession } from '~/server/utils/session'

export default defineEventHandler(async (event) => {
  const session = requireSession(event)

  if (!['worker', 'admin'].includes(session.role)) {
    throw createError({ statusCode: 403, statusMessage: 'No autorizado.' })
  }

  const today = new Intl.DateTimeFormat('en-CA', {
    timeZone: 'America/Caracas',
    year: 'numeric',
    month: '2-digit',
    day: '2-digit'
  }).format(new Date())

  const exists = await workerEntryExists(session.email, today)

  if (!exists) {
    throw createError({
      statusCode: 409,
      statusMessage: 'Debe registrar primero la entrada de hoy.'
    })
  }

  const timestamp = await markWorkerExit(session.email)

  return {
    ok: true,
    timestamp
  }
})
