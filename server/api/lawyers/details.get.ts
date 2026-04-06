import {
  findLawyerByCedulaOrId3,
  findLawyerByInpre,
  findLawyersByNeedle,
  getActiveSolvency,
  getInscriptionData,
  hasHistoricOperations,
  operationsByClient
} from '~/server/utils/repositories'
import { operationNotes } from '~/server/utils/normalizers'
import { requireSolvenciaAccess } from '~/server/utils/solvencia'

const parseYear = (raw: string) => {
  if (!raw) {
    return undefined
  }

  const value = Number(raw)
  const current = new Date().getFullYear()

  if (!Number.isInteger(value) || value < 1990 || value > current + 1) {
    return undefined
  }

  return value
}

export default defineEventHandler(async (event) => {
  requireSolvenciaAccess(event)

  const query = getQuery(event)
  const q = String(query.q || '').trim()
  const cod = String(query.cod || '').trim()
  const year = parseYear(String(query.year || ''))

  if (q.length < 3) {
    throw createError({
      statusCode: 400,
      statusMessage: 'Debe indicar una cedula o inpre valido.'
    })
  }

  const candidates = await findLawyersByNeedle(q)

  let selected = cod ? candidates.find((item) => item.CodClie === cod) : undefined

  if (!selected) {
    if (/^\d{3,7}$/.test(q)) {
      selected = (await findLawyerByInpre(q)) ?? undefined
    }

    if (!selected) {
      selected = (await findLawyerByCedulaOrId3(q)) ?? undefined
    }
  }

  if (!selected) {
    return {
      found: false,
      query: q,
      year,
      candidates
    }
  }

  const [inscription, solvency, rows, hasHistoric] = await Promise.all([
    getInscriptionData(selected.CodClie),
    getActiveSolvency(selected.CodClie),
    operationsByClient(selected.CodClie, year),
    hasHistoricOperations(selected.CodClie)
  ])

  const operations = rows.map((row) => ({
    ...row,
    notes: operationNotes(row)
  }))

  const groupedByYear = operations.reduce(
    (acc: Record<string, Array<any>>, row: any) => {
      const rawDate = String(row.FechaE || '')
      const key = /^\d{4}/.test(rawDate) ? rawDate.slice(0, 4) : 'Desconocido'
      if (!acc[key]) {
        acc[key] = []
      }

      if (acc[key].length < 25) {
        acc[key].push(row)
      }

      return acc
    },
    {} as Record<string, Array<any>>
  )

  const years = Object.keys(groupedByYear).sort((a, b) => {
    if (a === 'Desconocido') return 1
    if (b === 'Desconocido') return -1
    return Number(b) - Number(a)
  })

  return {
    found: true,
    query: q,
    year,
    selected,
    candidates,
    profile: {
      lawyer: selected,
      inscription,
      solvency
    },
    operations,
    groupedByYear: years.map((label) => ({
      year: label,
      items: groupedByYear[label]
    })),
    hasHistoric
  }
})
