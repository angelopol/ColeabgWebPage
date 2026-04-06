<script setup lang="ts">
definePageMeta({
  middleware: 'admin'
})

const overview = ref<null | Record<string, number>>(null)
const diagnostics = ref<null | {
  pending: Array<Record<string, string>>
  statusTwo: Array<Record<string, string>>
  duplicates: {
    codClie: Array<Record<string, string>>
    numeroD: Array<Record<string, string>>
    carnetNum: Array<Record<string, string>>
  }
}>(null)
const loading = ref(false)
const actionMessage = ref('')
const actionError = ref('')

const solvForm = reactive({
  ci: '',
  hasta: '',
  NumeroD: '',
  CarnetNum: '',
  CarnetNum2: ''
})

const today = new Date()
const defaultTo = today.toISOString().slice(0, 10)
const defaultFrom = new Date(today.getTime() - 1000 * 60 * 60 * 24 * 7)
  .toISOString()
  .slice(0, 10)

const assists = reactive({
  from: defaultFrom,
  to: defaultTo,
  rows: [] as Array<Record<string, string>>
})

const loadAdmin = async () => {
  loading.value = true
  actionError.value = ''

  try {
    const [overviewResponse, diagnosticResponse] = await Promise.all([
      $fetch<{ data: Record<string, number> }>('/api/admin/overview'),
      $fetch<typeof diagnostics.value>('/api/admin/diagnostics')
    ])

    overview.value = overviewResponse.data
    diagnostics.value = diagnosticResponse
  } catch (error: unknown) {
    actionError.value =
      (error as { data?: { statusMessage?: string } })?.data?.statusMessage ||
      'No fue posible cargar el panel administrativo.'
  } finally {
    loading.value = false
  }
}

const loadAssists = async () => {
  actionMessage.value = ''
  actionError.value = ''

  try {
    const response = await $fetch<{ rows: Array<Record<string, string>> }>('/api/admin/assists', {
      query: {
        from: assists.from,
        to: assists.to
      }
    })

    assists.rows = response.rows
  } catch (error: unknown) {
    actionError.value =
      (error as { data?: { statusMessage?: string } })?.data?.statusMessage ||
      'No se pudo consultar la asistencia.'
  }
}

const markSolved = async (numeroD: string) => {
  actionMessage.value = ''
  actionError.value = ''

  try {
    await $fetch('/api/admin/mark-solved', {
      method: 'POST',
      body: { numeroD }
    })

    actionMessage.value = `NumeroD ${numeroD} marcado como resuelto.`
    await loadAdmin()
  } catch (error: unknown) {
    actionError.value =
      (error as { data?: { statusMessage?: string } })?.data?.statusMessage ||
      'No se pudo actualizar el estado.'
  }
}

const createSolvencyRecord = async () => {
  actionMessage.value = ''
  actionError.value = ''

  try {
    const response = await $fetch<{ message: string }>('/api/admin/solvency', {
      method: 'POST',
      body: solvForm
    })

    actionMessage.value = response.message
    solvForm.ci = ''
    solvForm.hasta = ''
    solvForm.NumeroD = ''
    solvForm.CarnetNum = ''
    solvForm.CarnetNum2 = ''
    await loadAdmin()
  } catch (error: unknown) {
    actionError.value =
      (error as { data?: { statusMessage?: string } })?.data?.statusMessage ||
      'No se pudo registrar la solvencia.'
  }
}

await loadAdmin()
</script>

<template>
  <section class="space-y-6">
    <div class="panel flex flex-wrap items-center justify-between gap-3">
      <div>
        <p class="text-xs uppercase tracking-[0.25em] text-sand-200">Administrador</p>
        <h1 class="mt-2 text-4xl text-white">Centro de Control</h1>
      </div>
      <button class="btn-primary" type="button" @click="loadAdmin">Actualizar panel</button>
    </div>

    <p v-if="loading" class="text-sm text-slate-200">Cargando datos administrativos...</p>

    <div v-if="overview" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
      <article class="panel" v-for="(value, key) in overview" :key="key">
        <p class="text-xs uppercase tracking-[0.18em] text-slate-300">{{ key }}</p>
        <p class="mt-2 text-3xl text-sand-100">{{ value }}</p>
      </article>
    </div>

    <div class="grid gap-6 xl:grid-cols-2" v-if="diagnostics">
      <div class="panel">
        <h2 class="text-2xl text-white">Operaciones Fallidas / Pendientes</h2>
        <ul class="mt-4 max-h-[26rem] space-y-2 overflow-y-auto pr-1 text-sm">
          <li
            v-for="item in diagnostics.pending"
            :key="`pending-${item.NumeroD}`"
            class="rounded-xl border border-white/15 bg-white/10 p-3"
          >
            <p class="font-semibold text-sand-100">NumeroD: {{ item.NumeroD }} | CI: {{ item.CodClie }}</p>
            <p class="text-slate-300">{{ item.operation?.notes || 'Sin detalle SAFACT.' }}</p>
            <button class="btn-secondary mt-2 !px-3 !py-1.5" @click="markSolved(item.NumeroD || '')">
              Marcar resuelto
            </button>
          </li>
        </ul>
      </div>

      <div class="panel">
        <h2 class="text-2xl text-white">Status = 2</h2>
        <ul class="mt-4 max-h-[26rem] space-y-2 overflow-y-auto pr-1 text-sm">
          <li
            v-for="item in diagnostics.statusTwo"
            :key="`status-two-${item.NumeroD}`"
            class="rounded-xl border border-white/15 bg-white/10 p-3"
          >
            <p class="font-semibold text-sand-100">NumeroD: {{ item.NumeroD }} | CI: {{ item.CodClie }}</p>
            <p class="text-slate-300">{{ item.operation?.notes || 'Sin detalle SAFACT.' }}</p>
            <button class="btn-secondary mt-2 !px-3 !py-1.5" @click="markSolved(item.NumeroD || '')">
              Marcar resuelto
            </button>
          </li>
        </ul>
      </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-2" v-if="diagnostics">
      <div class="panel">
        <h2 class="text-2xl text-white">Duplicados</h2>
        <div class="mt-4 space-y-4 text-sm">
          <article>
            <p class="font-semibold text-sand-100">CodClie</p>
            <ul class="mt-2 space-y-1 text-slate-200">
              <li v-for="item in diagnostics.duplicates.codClie" :key="`cod-${item.CodClie}`">
                {{ item.CodClie }} · {{ item.total }} registros
              </li>
            </ul>
          </article>

          <article>
            <p class="font-semibold text-sand-100">NumeroD</p>
            <ul class="mt-2 space-y-1 text-slate-200">
              <li v-for="item in diagnostics.duplicates.numeroD" :key="`num-${item.NumeroD}`">
                {{ item.NumeroD }} · {{ item.total }} registros
              </li>
            </ul>
          </article>

          <article>
            <p class="font-semibold text-sand-100">Carnet</p>
            <ul class="mt-2 space-y-1 text-slate-200">
              <li v-for="item in diagnostics.duplicates.carnetNum" :key="`car-${item.CarnetNum2}`">
                {{ item.CarnetNum2 }} · {{ item.total }} registros
              </li>
            </ul>
          </article>
        </div>
      </div>

      <div class="panel">
        <h2 class="text-2xl text-white">Registrar Solvencia</h2>
        <form class="mt-4 grid gap-3 md:grid-cols-2" @submit.prevent="createSolvencyRecord">
          <input v-model="solvForm.ci" class="field" placeholder="Cedula" required />
          <input v-model="solvForm.hasta" class="field" placeholder="Solvente hasta" required />
          <input v-model="solvForm.NumeroD" class="field" placeholder="NumeroD" required />
          <input v-model="solvForm.CarnetNum" class="field" placeholder="Carnet (opcional)" />
          <input v-model="solvForm.CarnetNum2" class="field md:col-span-2" placeholder="Confirmar carnet" />
          <button class="btn-primary md:col-span-2" type="submit">Registrar solvencia</button>
        </form>
      </div>
    </div>

    <div class="panel">
      <h2 class="text-2xl text-white">Asistencia por Rango</h2>
      <form class="mt-4 grid gap-3 md:grid-cols-[1fr,1fr,auto]" @submit.prevent="loadAssists">
        <input v-model="assists.from" type="date" class="field" required />
        <input v-model="assists.to" type="date" class="field" required />
        <button class="btn-primary" type="submit">Consultar</button>
      </form>

      <ul class="mt-4 max-h-72 space-y-2 overflow-y-auto pr-1 text-sm">
        <li
          v-for="(row, idx) in assists.rows"
          :key="`${row.email}-${row.fecha}-${idx}`"
          class="rounded-xl border border-white/15 bg-white/10 p-3"
        >
          {{ row.fecha }} · {{ row.email }} · Entrada: {{ row.fecha_entrada || '-' }} · Salida: {{ row.fecha_salida || '-' }}
        </li>
      </ul>
    </div>

    <p
      v-if="actionMessage"
      class="rounded-xl border border-emerald-300/30 bg-emerald-300/10 px-3 py-2 text-sm text-emerald-100"
    >
      {{ actionMessage }}
    </p>

    <p
      v-if="actionError"
      class="rounded-xl border border-rose-300/30 bg-rose-300/10 px-3 py-2 text-sm text-rose-100"
    >
      {{ actionError }}
    </p>
  </section>
</template>
