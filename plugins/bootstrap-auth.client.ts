export default defineNuxtPlugin(async () => {
  const { user, refresh } = useAuth()

  if (!user.value) {
    await refresh()
  }
})
