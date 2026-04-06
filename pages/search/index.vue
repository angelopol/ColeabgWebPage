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

const fetchDetails = async (cod = '') => {
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

    if (!response.found) {
      message.value = 'No se encontraron coincidencias para la busqueda.'
    }
  } catch (error: unknown) {
    result.value = null
    message.value =
      (error as { data?: { statusMessage?: string } })?.data?.statusMessage ||
      'No se pudo completar la consulta.'
  } finally {
    pending.value = false
  }
}
</script>

<template>
  <section class="space-y-6">
    <div class="panel">
      <p class="text-xs uppercase tracking-[0.25em] text-sand-200">Consulta Publica</p>
      <h1 class="mt-2 text-4xl text-white">Verificar Operaciones</h1>
      <p class="mt-3 text-sm text-slate-200">
        Busca por cedula, Inpre o identificador y opcionalmente filtra por ano.
      </p>

      <form class="mt-6 grid gap-4 md:grid-cols-[2fr,1fr,auto]" @submit.prevent="fetchDetails()">
        <input
          v-model="form.q"
          type="text"
          class="field"
          placeholder="Cedula o Inpre"
          required
        />
        <input
          v-model="form.year"
          type="number"
          min="1990"
          :max="new Date().getFullYear() + 1"
          class="field"
          placeholder="Ano (opcional)"
        />
        <button class="btn-primary" :disabled="pending" type="submit">
          {{ pending ? 'Buscando...' : 'Buscar' }}
        </button>
      </form>

      <p v-if="message" class="mt-4 rounded-xl border border-rose-300/30 bg-rose-300/10 px-3 py-2 text-sm text-rose-100">
        {{ message }}
      </p>
    </div>

    <div v-if="result?.found" class="grid gap-6 lg:grid-cols-[1fr,1fr]">
      <div class="panel">
        <h2 class="text-2xl text-white">Perfil</h2>
        <div class="mt-4 space-y-1 text-sm text-slate-100">
          <p><strong>Nombre:</strong> {{ result.profile?.lawyer?.Descrip || 'Sin nombre' }}</p>
          <p><strong>Cedula:</strong> {{ result.profile?.lawyer?.CodClie || '-' }}</p>
          <p><strong>Inpre:</strong> {{ result.profile?.lawyer?.Clase || '-' }}</p>
          <p><strong>Solvente hasta:</strong> {{ result.profile?.solvency?.hasta || 'No registrado' }}</p>
          <p><strong>Carnet:</strong> {{ result.profile?.solvency?.CarnetNum2 || 'No posee' }}</p>
          <p><strong>Inscripcion:</strong> {{ result.profile?.inscription?.Fecha || 'No registrada' }}</p>
        </div>

        <div class="mt-5 flex flex-wrap gap-3">
          <NuxtLink
            v-if="result.selected?.CodClie"
            class="btn-primary"
            :to="{ path: `/operations/${result.selected.CodClie}`, query: { source: 'safact' } }"
          >
            Ver todo SAFACT
          </NuxtLink>
          <NuxtLink
            v-if="result.selected?.CodClie"
            class="btn-secondary"
            :to="{ path: `/operations/${result.selected.CodClie}`, query: { source: 'saacxc' } }"
          >
            Ver todo SAACXC
          </NuxtLink>
        </div>
      </div>

      <div class="panel">
        <h2 class="text-2xl text-white">Coincidencias</h2>
        <ul class="mt-4 space-y-2" v-if="result.candidates.length">
          <li
            v-for="item in result.candidates"
            :key="`${item.CodClie}-${item.Clase}`"
            class="flex items-center justify-between gap-3 rounded-xl border border-white/15 bg-white/10 px-3 py-2 text-sm"
          >
            <span>{{ item.CodClie }} | {{ item.Clase }} | {{ item.Descrip }}</span>
            <button class="btn-secondary !px-3 !py-1.5" @click="fetchDetails(item.CodClie || '')">
              Ver
            </button>
          </li>
        </ul>
        <p v-else class="mt-4 text-sm text-slate-200">Sin coincidencias adicionales.</p>
      </div>
    </div>

    <div v-if="result?.found" class="panel">
      <h2 class="text-3xl text-white">Operaciones</h2>
      <div class="mt-5 space-y-5">
        <article
          v-for="group in result.groupedByYear || []"
          :key="group.year"
          class="rounded-2xl border border-white/10 bg-white/10 p-4"
        >
          <h3 class="text-xl text-sand-100">Ano {{ group.year }} ({{ group.items.length }})</h3>
          <ul class="mt-3 space-y-2 text-sm">
            <li
              v-for="(row, idx) in group.items"
              :key="`${row.NumeroD || 'num'}-${idx}`"
              class="rounded-xl border border-white/10 bg-black/20 p-3"
            >
              <p class="font-semibold text-slate-100">{{ row.FechaE || 'Sin fecha' }} · {{ row.NumeroD || 'Sin numero' }}</p>
              <p class="text-slate-300">{{ row.OrdenC || '' }}</p>
              <p class="mt-1 text-slate-200">{{ row.notes || 'Sin observaciones.' }}</p>
            </li>
          </ul>
        </article>
      </div>
    </div>
  </section>
</template>
