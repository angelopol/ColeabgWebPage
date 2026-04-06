import {
  listDuplicateCarnet,
  listDuplicateCodClie,
  listDuplicateNumeroD,
  listPendingFailed,
  listStatusTwo,
  operationByNumeroD
} from '~/server/utils/repositories'
import { operationNotes } from '~/server/utils/normalizers'
import { requireAdminSession } from '~/server/utils/session'

export default defineEventHandler(async (event) => {
  requireAdminSession(event)

  const [pending, statusTwo, duplicateCod, duplicateNumeroD, duplicateCarnet] =
    await Promise.all([
      listPendingFailed(120),
      listStatusTwo(120),
      listDuplicateCodClie(),
      listDuplicateNumeroD(),
      listDuplicateCarnet()
    ])

  const enrich = async (numeroD?: string | null) => {
    if (!numeroD) {
      return null
    }

    const operation = await operationByNumeroD(numeroD)
    if (!operation) {
      return null
    }

    return {
      ...operation,
      notes: operationNotes(operation)
    }
  }

  const pendingExpanded = await Promise.all(
    pending.map(async (row) => ({
      ...row,
      operation: await enrich(row.NumeroD)
    }))
  )

  const statusTwoExpanded = await Promise.all(
    statusTwo.map(async (row) => ({
      ...row,
      operation: await enrich(row.NumeroD)
    }))
  )

  return {
    pending: pendingExpanded,
    statusTwo: statusTwoExpanded,
    duplicates: {
      codClie: duplicateCod,
      numeroD: duplicateNumeroD,
      carnetNum: duplicateCarnet
    }
  }
})
