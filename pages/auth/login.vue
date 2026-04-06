<script setup lang="ts">
const { refresh, user } = useAuth()

const form = reactive({
  user: '',
  password: ''
})

const pending = ref(false)
const errorMessage = ref('')

watchEffect(() => {
  if (user.value) {
    navigateTo('/dashboard')
  }
})

const submit = async () => {
  pending.value = true
  errorMessage.value = ''

  try {
    await $fetch('/api/auth/login', {
      method: 'POST',
      body: form
    })

    await refresh()
    await navigateTo('/dashboard')
  } catch (error: unknown) {
    errorMessage.value =
      (error as { data?: { statusMessage?: string } })?.data?.statusMessage ||
      'No fue posible iniciar sesion.'
  } finally {
    pending.value = false
  }
}
</script>

<template>
  <section class="mx-auto w-full max-w-lg">
    <div class="panel">
      <p class="mb-2 text-xs uppercase tracking-[0.25em] text-sand-200">Acceso de Afiliados</p>
      <h1 class="text-4xl text-white">Ingresa al Sistema</h1>

      <form class="mt-6 space-y-4" @submit.prevent="submit">
        <div>
          <label class="mb-1 block text-sm text-slate-200">Usuario o Email</label>
          <input v-model="form.user" type="text" class="field" required />
        </div>

        <div>
          <label class="mb-1 block text-sm text-slate-200">Contrasena</label>
          <input v-model="form.password" type="password" class="field" required />
        </div>

        <p v-if="errorMessage" class="rounded-xl border border-rose-300/30 bg-rose-300/10 px-3 py-2 text-sm text-rose-100">
          {{ errorMessage }}
        </p>

        <button :disabled="pending" class="btn-primary w-full disabled:opacity-70" type="submit">
          {{ pending ? 'Validando...' : 'Ingresar' }}
        </button>
      </form>

      <div class="mt-6 flex flex-wrap gap-3 text-sm">
        <NuxtLink to="/auth/register" class="btn-secondary">Crear cuenta</NuxtLink>
        <NuxtLink to="/workers" class="btn-secondary">Ingreso trabajadores</NuxtLink>
      </div>
    </div>
  </section>
</template>
