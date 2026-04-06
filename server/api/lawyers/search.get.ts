import {
  findLawyerByCedulaOrId3,
  findLawyerByInpre,
  findLawyersByNeedle
} from '~/server/utils/repositories'

export default defineEventHandler(async (event) => {
  const query = getQuery(event)
  const q = String(query.q || '').trim()

  if (q.length < 3) {
    throw createError({
      statusCode: 400,
      statusMessage: 'La busqueda debe tener al menos 3 caracteres.'
    })
  }

  let preferred = null
  if (/^\d{3,7}$/.test(q)) {
    preferred = await findLawyerByInpre(q)
  }

  if (!preferred) {
    preferred = await findLawyerByCedulaOrId3(q)
  }

  const candidates = await findLawyersByNeedle(q)

  return {
    query: q,
    preferred,
    candidates
  }
})
