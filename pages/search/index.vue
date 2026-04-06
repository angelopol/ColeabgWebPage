<script setup lang="ts">
const form = reactive({
  q: '',
  year: ''
})

const pending = ref(false)
const message = ref('')
const result = ref<null | {
  found: boolean
  selected?: Record<string, string>
  candidates: Array<Record<string, string>>
  profile?: {
    lawyer?: Record<string, string>
    inscription?: Record<string, string>
    solvency?: Record<string, string>
  }
  groupedByYear?: Array<{ year: string; items: Array<Record<string, string>> }>
  operations?: Array<Record<string, string>>
  hasHistoric?: boolean
}>(null)

const solvencia = reactive({
  checking: true,
  granted: false,
  password: '',
  pending: false,
  error: ''
})

const ui = reactive({
  showAdvancedModal: false,
  showProfileModal: false,
  showCandidatesModal: false,
  showOperations: true
})

const errorStatusCode = (error: unknown) => {
  const parsed = error as {
    statusCode?: number
    data?: {
      statusCode?: number
    }
  }

  return Number(parsed.statusCode || parsed.data?.statusCode || 0)
}

const checkSolvenciaSession = async () => {
  solvencia.checking = true

  try {
    const response = await $fetch<{ granted: boolean }>('/api/solvencia/status')
    solvencia.granted = Boolean(response.granted)
  } catch {
    solvencia.granted = false
  } finally {
    solvencia.checking = false
  }
}

const unlockSolvencia = async () => {
  solvencia.pending = true
  solvencia.error = ''
  result.value = null
  message.value = ''
  ui.showAdvancedModal = false
  ui.showProfileModal = false
  ui.showCandidatesModal = false

  try {
    await $fetch('/api/solvencia/access', {
      method: 'POST',
      body: {
        password: solvencia.password
      }
    })

    solvencia.granted = true
    solvencia.password = ''
  } catch (error: unknown) {
    solvencia.error =
      (error as { data?: { statusMessage?: string } })?.data?.statusMessage ||
      'No fue posible validar la clave de consulta.'
  } finally {
    solvencia.pending = false
  }
}

const fetchDetails = async (cod = '') => {
  if (!solvencia.granted) {
    solvencia.error =
      'Debes ingresar la clave de consulta para continuar.'
    return
  }

  if (form.q.trim().length < 3) {
    message.value = 'Indique al menos 3 caracteres para buscar.'
    return
  }

  pending.value = true
  message.value = ''

  try {
    const response = await $fetch('/api/lawyers/details', {
      query: {
        q: form.q.trim(),
        year: form.year.trim() || undefined,
        cod: cod || undefined
      }
    })

    result.value = response as typeof result.value
    ui.showOperations = true

    if (!response.found) {
      ui.showProfileModal = false
      ui.showCandidatesModal = false
      message.value = 'No se encontraron coincidencias para la busqueda.'
    }
  } catch (error: unknown) {
    if (errorStatusCode(error) === 401) {
      result.value = null
      message.value = ''
      ui.showProfileModal = false
      ui.showCandidatesModal = false
      solvencia.granted = false
      solvencia.error =
        'Tu sesion de consulta vencio. Vuelve a ingresar la clave.'
      return
    }

    result.value = null
    message.value =
      (error as { data?: { statusMessage?: string } })?.data?.statusMessage ||
      'No se pudo completar la consulta.'
  } finally {
    pending.value = false
  }
}

const hasResult = computed(() => Boolean(result.value?.found))
const groupedOperations = computed(() => result.value?.groupedByYear || [])

const applyAdvancedFilters = async () => {
  ui.showAdvancedModal = false
  await fetchDetails()
}

const clearAdvancedFilters = async () => {
  form.year = ''
  ui.showAdvancedModal = false

  if (hasResult.value) {
    await fetchDetails()
  }
}

const openProfileModal = () => {
  if (!hasResult.value) {
    return
  }

  ui.showProfileModal = true
}

const openCandidatesModal = () => {
  if (!hasResult.value) {
    return
  }

  ui.showCandidatesModal = true
}

const selectCandidate = async (cod = '') => {
  ui.showCandidatesModal = false
  await fetchDetails(cod)
}

onMounted(async () => {
  await checkSolvenciaSession()
})
</script>

<template>
  <section class="space-y-5">
    <div v-if="solvencia.checking" class="panel">
      <h1 class="text-3xl text-white">Consulta de Operaciones</h1>
      <p class="mt-3 text-sm text-slate-200">Validando sesion de consulta...</p>
    </div>

    <div v-else-if="!solvencia.granted" class="panel mx-auto max-w-2xl">
      <p class="text-xs uppercase tracking-[0.25em] text-sand-200">Acceso Protegido</p>
      <h1 class="mt-2 text-4xl text-white">Clave de Consulta</h1>
      <p class="mt-3 text-sm text-slate-200">
        Esta area requiere una clave de acceso para continuar.
      </p>
      <p class="mt-1 text-xs text-slate-300">
        Al validar la clave se guarda una cookie de sesion por 90 dias.
      </p>

      <form class="mt-6 space-y-4" @submit.prevent="unlockSolvencia">
        <input
          v-model="solvencia.password"
          type="password"
          class="field"
          placeholder="Clave de consulta"
          required
        />
        <button class="btn-primary w-full" :disabled="solvencia.pending" type="submit">
          {{ solvencia.pending ? 'Validando...' : 'Ingresar a consultas' }}
        </button>
      </form>

      <p
        v-if="solvencia.error"
        class="mt-4 rounded-xl border border-rose-300/30 bg-rose-300/10 px-3 py-2 text-sm text-rose-100"
      >
        {{ solvencia.error }}
      </p>
    </div>

    <template v-else>
      <div class="panel space-y-4">
        <p class="text-xs uppercase tracking-[0.25em] text-sand-200">Consulta Publica</p>
        <h1 class="mt-2 text-3xl text-white sm:text-4xl">Verificar Operaciones</h1>
        <p class="mt-3 text-sm text-slate-200">
          Busqueda compacta para movil. Usa acciones para abrir filtros y detalles.
        </p>

        <form class="grid gap-3 sm:grid-cols-[1fr,auto]" @submit.prevent="fetchDetails()">
          <input
            v-model="form.q"
            type="text"
            class="field"
            placeholder="Cedula o Inpre"
            required
          />
          <button class="btn-primary w-full sm:w-auto" :disabled="pending" type="submit">
            {{ pending ? 'Buscando...' : 'Buscar' }}
          </button>
        </form>

        <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap">
          <button class="btn-secondary !w-full !px-3 !py-2 text-xs sm:!w-auto sm:text-sm" type="button" @click="ui.showAdvancedModal = true">
            Filtros
          </button>
          <button class="btn-secondary !w-full !px-3 !py-2 text-xs sm:!w-auto sm:text-sm" type="button" :disabled="!hasResult" @click="openProfileModal">
            Perfil
          </button>
          <button class="btn-secondary !w-full !px-3 !py-2 text-xs sm:!w-auto sm:text-sm" type="button" :disabled="!hasResult" @click="openCandidatesModal">
            Coincidencias
          </button>
          <button
            class="btn-secondary !w-full !px-3 !py-2 text-xs sm:!w-auto sm:text-sm"
            type="button"
            :disabled="!hasResult"
            @click="ui.showOperations = !ui.showOperations"
          >
            {{ ui.showOperations ? 'Ocultar operaciones' : 'Mostrar operaciones' }}
          </button>
        </div>

        <p class="text-xs text-slate-300">
          Filtro activo: {{ form.year ? `Año ${form.year}` : 'Sin filtro por año' }}
        </p>

        <p v-if="message" class="mt-4 rounded-xl border border-rose-300/30 bg-rose-300/10 px-3 py-2 text-sm text-rose-100">
          {{ message }}
        </p>
      </div>

      <div v-if="hasResult" class="panel">
        <div class="flex items-center justify-between gap-3">
          <h2 class="text-2xl text-white">Operaciones</h2>
          <p class="text-xs text-slate-300">{{ groupedOperations.length }} grupos por año</p>
        </div>

        <div v-if="ui.showOperations" class="mt-4 space-y-3">
          <article
            v-for="group in groupedOperations"
            :key="group.year"
            class="rounded-2xl border border-white/10 bg-white/10 p-3 sm:p-4"
          >
            <details>
              <summary class="cursor-pointer list-none text-sm font-semibold text-sand-100 sm:text-base">
                Año {{ group.year }} ({{ group.items.length }})
              </summary>

              <ul class="mt-3 space-y-2 text-sm">
                <li
                  v-for="(row, idx) in group.items"
                  :key="`${row.NumeroD || 'num'}-${idx}`"
                  class="rounded-xl border border-white/10 bg-black/20 p-3"
                >
                  <p class="font-semibold text-slate-100">{{ row.FechaE || 'Sin fecha' }} · {{ row.NumeroD || 'Sin numero' }}</p>
                  <p class="text-slate-300">{{ row.OrdenC || '' }}</p>
                  <details class="mt-2">
                    <summary class="cursor-pointer list-none text-xs text-sand-100">Ver notas</summary>
                    <p class="mt-1 text-slate-200">{{ row.notes || 'Sin observaciones.' }}</p>
                  </details>
                </li>
              </ul>
            </details>
          </article>
        </div>

        <p v-else class="mt-3 text-sm text-slate-300">
          Las operaciones estan ocultas para reducir elementos en pantalla.
        </p>
      </div>

      <div
        v-if="ui.showAdvancedModal"
        class="fixed inset-0 z-40 flex items-end justify-center bg-black/70 p-0 sm:items-center sm:p-4"
        @click.self="ui.showAdvancedModal = false"
      >
        <div class="w-full max-h-[92vh] overflow-y-auto rounded-t-3xl border border-white/20 bg-ink p-5 sm:max-w-lg sm:rounded-3xl">
          <div class="flex items-center justify-between gap-3">
            <h3 class="text-lg text-white">Filtro avanzado</h3>
            <button class="btn-secondary !px-3 !py-1.5 text-xs" type="button" @click="ui.showAdvancedModal = false">
              Cerrar
            </button>
          </div>

          <form class="mt-4 space-y-3" @submit.prevent="applyAdvancedFilters">
            <input
              v-model="form.year"
              type="number"
              min="1990"
              :max="new Date().getFullYear() + 1"
              class="field"
              placeholder="Año (opcional)"
            />

            <div class="grid gap-2 sm:grid-cols-2">
              <button class="btn-primary w-full" type="submit">Aplicar filtro</button>
              <button class="btn-secondary w-full" type="button" @click="clearAdvancedFilters">
                Limpiar año
              </button>
            </div>
          </form>
        </div>
      </div>

      <div
        v-if="ui.showProfileModal && hasResult"
        class="fixed inset-0 z-40 flex items-end justify-center bg-black/70 p-0 sm:items-center sm:p-4"
        @click.self="ui.showProfileModal = false"
      >
        <div class="w-full max-h-[92vh] overflow-y-auto rounded-t-3xl border border-white/20 bg-ink p-5 sm:max-w-xl sm:rounded-3xl">
          <div class="flex items-center justify-between gap-3">
            <h3 class="text-lg text-white">Perfil del abogado</h3>
            <button class="btn-secondary !px-3 !py-1.5 text-xs" type="button" @click="ui.showProfileModal = false">
              Cerrar
            </button>
          </div>

          <div class="mt-4 space-y-1 text-sm text-slate-100">
            <p><strong>Nombre:</strong> {{ result?.profile?.lawyer?.Descrip || 'Sin nombre' }}</p>
            <p><strong>Cedula:</strong> {{ result?.profile?.lawyer?.CodClie || '-' }}</p>
            <p><strong>Inpre:</strong> {{ result?.profile?.lawyer?.Clase || '-' }}</p>
            <p><strong>Solvente hasta:</strong> {{ result?.profile?.solvency?.hasta || 'No registrado' }}</p>
            <p><strong>Carnet:</strong> {{ result?.profile?.solvency?.CarnetNum2 || 'No posee' }}</p>
            <p><strong>Inscripcion:</strong> {{ result?.profile?.inscription?.Fecha || 'No registrada' }}</p>
          </div>

          <div class="mt-4 grid gap-2 sm:grid-cols-2">
            <NuxtLink
              v-if="result?.selected?.CodClie"
              class="btn-primary w-full"
              :to="{ path: `/operations/${result.selected.CodClie}`, query: { source: 'safact' } }"
            >
              Ver todo SAFACT
            </NuxtLink>
            <NuxtLink
              v-if="result?.selected?.CodClie"
              class="btn-secondary w-full"
              :to="{ path: `/operations/${result.selected.CodClie}`, query: { source: 'saacxc' } }"
            >
              Ver todo SAACXC
            </NuxtLink>
          </div>
        </div>
      </div>

      <div
        v-if="ui.showCandidatesModal && hasResult"
        class="fixed inset-0 z-40 flex items-end justify-center bg-black/70 p-0 sm:items-center sm:p-4"
        @click.self="ui.showCandidatesModal = false"
      >
        <div class="w-full max-h-[92vh] overflow-y-auto rounded-t-3xl border border-white/20 bg-ink p-5 sm:max-w-2xl sm:rounded-3xl">
          <div class="flex items-center justify-between gap-3">
            <h3 class="text-lg text-white">Coincidencias</h3>
            <button class="btn-secondary !px-3 !py-1.5 text-xs" type="button" @click="ui.showCandidatesModal = false">
              Cerrar
            </button>
          </div>

          <ul v-if="result?.candidates?.length" class="mt-4 space-y-2">
            <li
              v-for="item in result.candidates"
              :key="`${item.CodClie}-${item.Clase}`"
              class="flex items-center justify-between gap-3 rounded-xl border border-white/15 bg-white/10 px-3 py-2 text-sm"
            >
              <span class="min-w-0 truncate">{{ item.CodClie }} | {{ item.Clase }} | {{ item.Descrip }}</span>
              <button class="btn-secondary !px-3 !py-1.5 text-xs" type="button" @click="selectCandidate(item.CodClie || '')">
                Seleccionar
              </button>
            </li>
          </ul>
          <p v-else class="mt-4 text-sm text-slate-200">Sin coincidencias adicionales.</p>
        </div>
      </div>
    </template>
  </section>
</template>
