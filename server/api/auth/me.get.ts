import { getUserSession } from '~/server/utils/session'

export default defineEventHandler((event) => {
  return {
    user: getUserSession(event)
  }
})
