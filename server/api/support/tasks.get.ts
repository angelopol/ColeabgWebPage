import { listSupportTasks } from '~/server/utils/support-tasks'
import { requireSupportAccess } from '~/server/utils/support-access'

export default defineEventHandler(async (event) => {
  requireSupportAccess(event)

  const query = getQuery(event)

  const status = String(query.status || '').trim().toLowerCase()
  const fromDate = String(query.fromDate || '').trim()
  const toDate = String(query.toDate || '').trim()
  const keyword = String(query.keyword || '').trim()

  const tasks = await listSupportTasks({
    status: status && status !== 'all' ? status : undefined,
    fromDate: fromDate || undefined,
    toDate: toDate || undefined,
    keyword: keyword || undefined
  })

  return {
    tasks
  }
})
