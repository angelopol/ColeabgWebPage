import { adminOverview } from '~/server/utils/repositories'
import { requireAdminSession } from '~/server/utils/session'

export default defineEventHandler(async (event) => {
  requireAdminSession(event)

  return {
    data: await adminOverview()
  }
})
