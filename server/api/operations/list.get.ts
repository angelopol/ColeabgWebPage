import { allOperationsSAACXC, allOperationsSAFACT } from '~/server/utils/repositories'
import { operationNotes } from '~/server/utils/normalizers'
import { requireSolvenciaAccess } from '~/server/utils/solvencia'

export default defineEventHandler(async (event) => {
  requireSolvenciaAccess(event)

  const query = getQuery(event)
  const ci = String(query.ci || '').trim()
  const source = String(query.source || 'safact').toLowerCase()

  if (!ci) {
    throw createError({
      statusCode: 400,
      statusMessage: 'Debe indicar el parametro ci.'
    })
  }

  const rows =
    source === 'saacxc'
      ? await allOperationsSAACXC(ci)
      : await allOperationsSAFACT(ci)

  return {
    source,
    ci,
    rows: rows.map((row) => ({
      ...row,
      notes: operationNotes(row)
    }))
  }
})
