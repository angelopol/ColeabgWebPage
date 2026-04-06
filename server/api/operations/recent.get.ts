import { operationsByClient } from '~/server/utils/repositories'
import { operationNotes } from '~/server/utils/normalizers'
import { requireSession } from '~/server/utils/session'

export default defineEventHandler(async (event) => {
  const session = requireSession(event)

  if (!session.codClie) {
    return {
      rows: []
    }
  }

  const currentYear = new Date().getFullYear()
  const rows = await operationsByClient(session.codClie, currentYear)

  return {
    year: currentYear,
    rows: rows.map((row) => ({
      ...row,
      notes: operationNotes(row)
    }))
  }
})
