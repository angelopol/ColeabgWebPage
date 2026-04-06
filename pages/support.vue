<script setup lang="ts">
const form = reactive({
  name: '',
  email: '',
  message: ''
})

const pending = ref(false)
const status = ref('')
const errorMessage = ref('')

const submit = async () => {
  pending.value = true
  status.value = ''
  errorMessage.value = ''

  try {
    const response = await $fetch<{ message: string }>('/api/contact', {
      method: 'POST',
      body: form
    })

    status.value = response.message
    form.name = ''
    form.email = ''
    form.message = ''
  } catch (error: unknown) {
    errorMessage.value =
      (error as { data?: { statusMessage?: string } })?.data?.statusMessage ||
      'No fue posible enviar el mensaje.'
  } finally {
    pending.value = false
  }
}
</script>

<template>
  <section class="mx-auto w-full max-w-2xl">
    <div class="panel">
      <h1 class="text-4xl text-white">Soporte</h1>
      <p class="mt-2 text-sm text-slate-200">
        Si necesitas ayuda con registro, operaciones o asistencia, escribe aqui.
      </p>

      <form class="mt-6 space-y-4" @submit.prevent="submit">
        <div>
          <label class="mb-1 block text-sm text-slate-200">Nombre</label>
          <input v-model="form.name" class="field" required />
        </div>

        <div>
          <label class="mb-1 block text-sm text-slate-200">Email</label>
          <input v-model="form.email" type="email" class="field" required />
        </div>

        <div>
          <label class="mb-1 block text-sm text-slate-200">Mensaje</label>
          <textarea v-model="form.message" class="field min-h-40" required />
        </div>

        <button class="btn-primary w-full" :disabled="pending" type="submit">
          {{ pending ? 'Enviando...' : 'Enviar mensaje' }}
        </button>
      </form>

      <p
        v-if="status"
        class="mt-4 rounded-xl border border-emerald-300/30 bg-emerald-300/10 px-3 py-2 text-sm text-emerald-100"
      >
        {{ status }}
      </p>
      <p
        v-if="errorMessage"
        class="mt-4 rounded-xl border border-rose-300/30 bg-rose-300/10 px-3 py-2 text-sm text-rose-100"
      >
        {{ errorMessage }}
      </p>
    </div>
  </section>
</template>
