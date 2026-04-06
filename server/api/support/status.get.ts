import { hasSupportAccess } from '~/server/utils/support-access'

export default defineEventHandler((event) => {
  return {
    granted: hasSupportAccess(event)
  }
})
