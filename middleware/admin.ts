export default defineNuxtRouteMiddleware(async () => {
  const { user, refresh } = useAuth()

  if (!user.value) {
    await refresh()
  }

  if (!user.value) {
    return navigateTo('/auth/login')
  }

  if (user.value.role !== 'admin') {
    return navigateTo('/dashboard')
  }
})
