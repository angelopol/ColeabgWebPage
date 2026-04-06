<script setup lang="ts">
const form = reactive({
  correo: '',
  correo2: '',
  contra: '',
  contra2: '',
  ci: '',
  ip: ''
})

const pending = ref(false)
const message = ref('')
const ok = ref(false)

const submit = async () => {
  pending.value = true
  message.value = ''

  try {
    const response = await $fetch<{ message: string }>('/api/auth/register', {
      method: 'POST',
      body: form
    })

    ok.value = true
    message.value = response.message
  } catch (error: unknown) {
    ok.value = false
    message.value =
      (error as { data?: { statusMessage?: string } })?.data?.statusMessage ||
      'No fue posible completar el registro.'
  } finally {
    pending.value = false
  }
}
</script>

<template>
  <section class="mx-auto w-full max-w-3xl">
    <div class="panel">
      <p class="mb-2 text-xs uppercase tracking-[0.25em] text-sand-200">Registro</p>
      <h1 class="text-4xl text-white">Crea tu cuenta</h1>

      <form class="mt-6 grid gap-4 md:grid-cols-2" @submit.prevent="submit">
        <div>
          <label class="mb-1 block text-sm text-slate-200">Email</label>
          <input v-model="form.correo" type="email" class="field" required />
        </div>

        <div>
          <label class="mb-1 block text-sm text-slate-200">Confirmar Email</label>
          <input v-model="form.correo2" type="email" class="field" required />
        </div>

        <div>
          <label class="mb-1 block text-sm text-slate-200">Contrasena</label>
          <input v-model="form.contra" type="password" minlength="8" class="field" required />
        </div>

        <div>
          <label class="mb-1 block text-sm text-slate-200">Confirmar Contrasena</label>
          <input v-model="form.contra2" type="password" minlength="8" class="field" required />
        </div>

        <div>
          <label class="mb-1 block text-sm text-slate-200">Cedula</label>
          <input v-model="form.ci" type="text" class="field" required />
        </div>

        <div>
          <label class="mb-1 block text-sm text-slate-200">Inpre</label>
          <input v-model="form.ip" type="text" class="field" required />
        </div>

        <div class="md:col-span-2">
          <button :disabled="pending" class="btn-primary w-full disabled:opacity-70" type="submit">
            {{ pending ? 'Procesando...' : 'Registrar' }}
          </button>
        </div>
      </form>

      <p
        v-if="message"
        class="mt-4 rounded-xl px-3 py-2 text-sm"
        :class="ok ? 'border border-emerald-300/30 bg-emerald-300/10 text-emerald-100' : 'border border-rose-300/30 bg-rose-300/10 text-rose-100'"
      >
        {{ message }}
      </p>

      <div class="mt-5">
        <NuxtLink to="/auth/login" class="btn-secondary">Ya tengo cuenta</NuxtLink>
      </div>
    </div>
  </section>
</template>
