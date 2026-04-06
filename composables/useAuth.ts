import type { UserSession } from '~/types/domain'

export const useAuth = () => {
  const user = useState<UserSession | null>('auth-user', () => null)
  const loading = useState<boolean>('auth-loading', () => false)

  const refresh = async () => {
    loading.value = true
    try {
      const response = await $fetch<{ user: UserSession | null }>('/api/auth/me')
      user.value = response.user
    } catch {
      user.value = null
    } finally {
      loading.value = false
    }
  }

  const logout = async () => {
    await $fetch('/api/auth/logout', { method: 'POST' })
    user.value = null
    await navigateTo('/auth/login')
  }

  return {
    user,
    loading,
    refresh,
    logout
  }
}
