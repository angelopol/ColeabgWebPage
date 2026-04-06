<script setup lang="ts">
const { user, refresh } = useAuth()

const loginForm = reactive({
  user: ''
})

const message = ref('')
const errorMessage = ref('')
const pendingLogin = ref(false)
const pendingEntry = ref(false)
const pendingExit = ref(false)
const geolocationMessage = ref('Activa geolocalizacion para habilitar los botones.')
const coords = ref<{ lat: number; lng: number } | null>(null)
const canMark = ref(false)
let watcher: number | null = null

const isWorkerLogged = computed(() => ['worker', 'admin'].includes(user.value?.role || ''))
const workerName = computed(() => {
  return (user.value?.displayName || user.value?.email || '').toLowerCase()
})

const isInsideArea = (lat: number, lng: number, name: string) => {
  const insideCampus =
    lat > 10.2244 && lat < 10.2248 && lng > -68.0066 && lng < -68.0056

  if (insideCampus) {
    return true
  }

  if (name.includes('jesus ramirez') || name.includes('francisco colina')) {
    return lat > 10.2214 && lat < 10.2258 && lng > -68.0075 && lng < -68.0047
  }

  if (name.includes('angelo polgrossi') || name.includes('angelo')) {
    return true
  }

  return false
}

const stopWatcher = () => {
  if (watcher !== null && navigator?.geolocation) {
    navigator.geolocation.clearWatch(watcher)
  }
  watcher = null
}

const startWatcher = () => {
  if (typeof window === 'undefined' || !isWorkerLogged.value) {
    return
  }

  if (!('geolocation' in navigator)) {
    canMark.value = false
    geolocationMessage.value = 'Tu navegador no permite geolocalizacion.'
    return
  }

  stopWatcher()

  watcher = navigator.geolocation.watchPosition(
    (position) => {
      const lat = position.coords.latitude
      const lng = position.coords.longitude
      coords.value = { lat, lng }

      if (isInsideArea(lat, lng, workerName.value)) {
        canMark.value = true
        geolocationMessage.value = 'Ubicacion valida. Puedes marcar asistencia.'
      } else {
        canMark.value = false
        geolocationMessage.value =
          'No te encuentras en un area autorizada para registrar asistencia.'
      }
    },
    (error) => {
      canMark.value = false
      geolocationMessage.value = `Error de ubicacion: ${error.message}`
    },
    {
      enableHighAccuracy: true,
      maximumAge: 0,
      timeout: 8000
    }
  )
}

watch(isWorkerLogged, (value) => {
  if (value) {
    startWatcher()
  } else {
    stopWatcher()
    canMark.value = false
  }
})

onMounted(async () => {
  await refresh()
  if (isWorkerLogged.value) {
    startWatcher()
  }
})

onBeforeUnmount(() => {
  stopWatcher()
})

const loginWorker = async () => {
  pendingLogin.value = true
  message.value = ''
  errorMessage.value = ''

  try {
    await $fetch('/api/workers/login', {
      method: 'POST',
      body: loginForm
    })

    await refresh()
    message.value = 'Sesion de trabajador iniciada.'
  } catch (error: unknown) {
    errorMessage.value =
      (error as { data?: { statusMessage?: string } })?.data?.statusMessage ||
      'No fue posible autenticar al trabajador.'
  } finally {
    pendingLogin.value = false
  }
}

const markEntry = async () => {
  pendingEntry.value = true
  message.value = ''
  errorMessage.value = ''

  try {
    await $fetch('/api/workers/attendance/entry', { method: 'POST' })
    message.value = 'Entrada registrada correctamente.'
  } catch (error: unknown) {
    errorMessage.value =
      (error as { data?: { statusMessage?: string } })?.data?.statusMessage ||
      'No se pudo registrar la entrada.'
  } finally {
    pendingEntry.value = false
  }
}

const markExit = async () => {
  pendingExit.value = true
  message.value = ''
  errorMessage.value = ''

  try {
    await $fetch('/api/workers/attendance/exit', { method: 'POST' })
    message.value = 'Salida registrada correctamente.'
  } catch (error: unknown) {
    errorMessage.value =
      (error as { data?: { statusMessage?: string } })?.data?.statusMessage ||
      'No se pudo registrar la salida.'
  } finally {
    pendingExit.value = false
  }
}
</script>

<template>
  <section class="mx-auto w-full max-w-3xl space-y-6">
    <div class="panel">
      <h1 class="text-4xl text-white">Asistencia de Trabajadores</h1>
      <p class="mt-2 text-sm text-slate-200">
        Registro de entrada y salida con verificacion de geolocalizacion.
      </p>
    </div>

    <div v-if="!isWorkerLogged" class="panel">
      <h2 class="text-2xl text-white">Ingreso</h2>
      <form class="mt-4 space-y-4" @submit.prevent="loginWorker">
        <div>
          <label class="mb-1 block text-sm text-slate-200">Correo de trabajador</label>
          <input v-model="loginForm.user" type="email" class="field" required />
        </div>

        <button :disabled="pendingLogin" class="btn-primary w-full" type="submit">
          {{ pendingLogin ? 'Validando...' : 'Ingresar' }}
        </button>
      </form>
    </div>

    <div v-else class="panel space-y-5">
      <p class="text-sm text-slate-100">Sesion activa: {{ user?.email }}</p>
      <p class="rounded-xl border border-white/15 bg-white/10 px-3 py-2 text-sm text-slate-100">
        {{ geolocationMessage }}
      </p>
      <p v-if="coords" class="text-xs text-slate-300">
        Latitud: {{ coords.lat.toFixed(6) }} | Longitud: {{ coords.lng.toFixed(6) }}
      </p>

      <div class="grid gap-3 sm:grid-cols-2">
        <button
          class="btn-primary w-full"
          type="button"
          :disabled="!canMark || pendingEntry"
          @click="markEntry"
        >
          {{ pendingEntry ? 'Registrando...' : 'Marcar entrada' }}
        </button>

        <button
          class="btn-secondary w-full"
          type="button"
          :disabled="!canMark || pendingExit"
          @click="markExit"
        >
          {{ pendingExit ? 'Registrando...' : 'Marcar salida' }}
        </button>
      </div>
    </div>

    <p
      v-if="message"
      class="rounded-xl border border-emerald-300/30 bg-emerald-300/10 px-3 py-2 text-sm text-emerald-100"
    >
      {{ message }}
    </p>

    <p
      v-if="errorMessage"
      class="rounded-xl border border-rose-300/30 bg-rose-300/10 px-3 py-2 text-sm text-rose-100"
    >
      {{ errorMessage }}
    </p>
  </section>
</template>
