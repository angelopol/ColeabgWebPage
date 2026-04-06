export default defineNuxtRouteMiddleware(async () => {
  try {
    const status = await $fetch<{ granted: boolean }>('/api/solvencia/status')
    if (!status.granted) {
      return navigateTo('/search')
    }
  } catch {
    return navigateTo('/search')
  }
})
