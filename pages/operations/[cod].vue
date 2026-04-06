<script setup lang="ts">
definePageMeta({
  middleware: 'solvencia'
})

const route = useRoute()

const source = computed(() => {
  const value = String(route.query.source || 'safact').toLowerCase()
  return value === 'saacxc' ? 'saacxc' : 'safact'
})

const { data, pending, error, refresh } = await useAsyncData(
  () => `operations-${route.params.cod}-${source.value}`,
  () =>
    $fetch<{ ci: string; source: string; rows: Array<Record<string, string>> }>(
      '/api/operations/list',
      {
        query: {
          ci: String(route.params.cod || ''),
          source: source.value
        }
      }
    ),
  {
    watch: [() => route.params.cod, source]
  }
)
</script>

<template>
  <section class="space-y-6">
    <div class="panel">
      <h1 class="text-4xl text-white">Operaciones {{ source.toUpperCase() }}</h1>
      <p class="mt-2 text-sm text-slate-200">Cliente: {{ route.params.cod }}</p>
      <div class="mt-4 flex flex-wrap gap-3">
        <NuxtLink
          class="btn-secondary"
          :to="{ path: route.path, query: { source: source === 'safact' ? 'saacxc' : 'safact' } }"
        >
          Ver {{ source === 'safact' ? 'SAACXC' : 'SAFACT' }}
        </NuxtLink>
        <NuxtLink class="btn-secondary" to="/search">Volver al buscador</NuxtLink>
        <button class="btn-primary" type="button" @click="refresh">Actualizar</button>
      </div>
    </div>

    <div class="panel">
      <p v-if="pending" class="text-sm text-slate-200">Cargando operaciones...</p>
      <p v-else-if="error" class="text-sm text-rose-100">No fue posible consultar las operaciones.</p>
      <p v-else-if="!data?.rows.length" class="text-sm text-slate-200">Sin operaciones para este origen.</p>

      <ul v-else class="space-y-3">
        <li
          v-for="(row, idx) in data.rows"
          :key="`${row.NumeroD || row.Document || 'row'}-${idx}`"
          class="rounded-2xl border border-white/15 bg-white/10 p-4"
        >
          <p class="text-sm font-semibold text-sand-100">
            {{ row.FechaE || 'Sin fecha' }} · {{ row.NumeroD || row.Document || 'Sin documento' }}
          </p>
          <p class="mt-1 text-xs text-slate-300">{{ row.OrdenC || '' }}</p>
          <p class="mt-1 text-sm text-slate-200">{{ row.notes || 'Sin observaciones.' }}</p>
        </li>
      </ul>
    </div>
  </section>
</template>
