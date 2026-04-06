import { hasSolvenciaAccess } from '~/server/utils/solvencia'

export default defineEventHandler((event) => {
  return {
    granted: hasSolvenciaAccess(event)
  }
})
