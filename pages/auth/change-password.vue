<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const form = reactive({
  contra: '',
  contra2: '',
  contranew: '',
  contranew2: ''
})

const pending = ref(false)
const message = ref('')
const ok = ref(false)

const submit = async () => {
  pending.value = true
  message.value = ''

  try {
    const response = await $fetch<{ message: string }>('/api/auth/change-password', {
      method: 'POST',
      body: form
    })
    ok.value = true
    message.value = response.message
    form.contra = ''
    form.contra2 = ''
    form.contranew = ''
    form.contranew2 = ''
  } catch (error: unknown) {
    ok.value = false
    message.value =
      (error as { data?: { statusMessage?: string } })?.data?.statusMessage ||
      'No se pudo actualizar la contrasena.'
  } finally {
    pending.value = false
  }
}
</script>

<template>
  <section class="mx-auto w-full max-w-2xl">
    <div class="panel">
      <h1 class="text-3xl text-white">Cambiar Contrasena</h1>
      <form class="mt-6 grid gap-4 md:grid-cols-2" @submit.prevent="submit">
        <div>
          <label class="mb-1 block text-sm text-slate-200">Contrasena actual</label>
          <input v-model="form.contra" type="password" minlength="8" class="field" required />
        </div>
        <div>
          <label class="mb-1 block text-sm text-slate-200">Confirmar actual</label>
          <input v-model="form.contra2" type="password" minlength="8" class="field" required />
        </div>
        <div>
          <label class="mb-1 block text-sm text-slate-200">Nueva contrasena</label>
          <input v-model="form.contranew" type="password" minlength="8" class="field" required />
        </div>
        <div>
          <label class="mb-1 block text-sm text-slate-200">Confirmar nueva</label>
          <input v-model="form.contranew2" type="password" minlength="8" class="field" required />
        </div>

        <div class="md:col-span-2">
          <button :disabled="pending" class="btn-primary w-full disabled:opacity-70" type="submit">
            {{ pending ? 'Actualizando...' : 'Actualizar contrasena' }}
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
    </div>
  </section>
</template>
