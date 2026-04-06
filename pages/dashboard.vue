<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const { user, refresh: refreshAuth } = useAuth()

const { data, pending, error, refresh } = await useAsyncData(
  'recent-dashboard-ops',
  () => $fetch<{ year: number; rows: Array<Record<string, string>> }>('/api/operations/recent'),
  {
    default: () => ({
      year: new Date().getFullYear(),
      rows: []
    })
  }
)

onMounted(async () => {
  if (!user.value) {
    await refreshAuth()
  }

  await refresh()
})
</script>

<template>
  <section class="space-y-6">
    <div class="panel">
      <p class="text-xs uppercase tracking-[0.25em] text-sand-200">Area Privada</p>
      <h1 class="mt-2 text-4xl text-white">Bienvenido {{ user?.displayName || user?.email }}</h1>
      <p class="mt-3 max-w-3xl text-sm text-slate-200">
        Desde aqui puedes revisar tus operaciones recientes, consultar historicos y gestionar tu cuenta.
      </p>
      <div class="mt-5 flex flex-wrap gap-3">
        <NuxtLink class="btn-primary" to="/search">Buscar operaciones</NuxtLink>
        <NuxtLink
          v-if="user?.codClie"
          class="btn-secondary"
          :to="{ path: `/operations/${user.codClie}`, query: { source: 'safact' } }"
        >
          Ver todas mis operaciones
        </NuxtLink>
        <NuxtLink class="btn-secondary" to="/auth/change-password">Cambiar contrasena</NuxtLink>
      </div>
    </div>

    <div class="panel">
      <div class="mb-4 flex items-center justify-between gap-3">
        <h2 class="text-3xl text-white">Operaciones recientes {{ data.year }}</h2>
        <button class="btn-secondary" type="button" @click="refresh">Actualizar</button>
      </div>

      <p v-if="pending" class="text-sm text-slate-200">Cargando operaciones...</p>
      <p v-else-if="error" class="text-sm text-rose-100">No se pudieron cargar las operaciones.</p>
      <p v-else-if="!data.rows.length" class="text-sm text-slate-200">No hay operaciones recientes para este ano.</p>

      <ul v-else class="space-y-3">
        <li
          v-for="(row, idx) in data.rows"
          :key="`${row.NumeroD || 'sin-numero'}-${idx}`"
          class="rounded-2xl border border-white/15 bg-white/10 p-4"
        >
          <p class="text-sm font-semibold text-sand-100">
            {{ row.FechaE || 'Sin fecha' }} · {{ row.NumeroD || 'Sin numero' }}
          </p>
          <p class="mt-1 text-xs text-slate-200">
            {{ row.OrdenC || '' }}
          </p>
          <p class="mt-1 text-sm text-slate-100">
            {{ row.notes || 'Sin observaciones.' }}
          </p>
        </li>
      </ul>
    </div>
  </section>
</template>
