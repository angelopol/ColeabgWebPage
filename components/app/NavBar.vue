<script setup lang="ts">
import { computed } from 'vue'
import { useAuth } from '~/composables/useAuth'

const { user, logout } = useAuth()

const links = computed(() => {
  if (!user.value) {
    return [
      { to: '/', label: 'Inicio' },
      { to: '/auth/login', label: 'Ingresar' },
      { to: '/auth/register', label: 'Registro' },
      { to: '/search', label: 'Consulta' },
      { to: '/support', label: 'Soporte' }
    ]
  }

  const base = [
    { to: '/dashboard', label: 'Dashboard' },
    { to: '/search', label: 'Buscador' },
    { to: '/workers', label: 'Asistencia' },
    { to: '/support', label: 'Soporte' }
  ]

  if (user.value.role === 'admin') {
    base.push({ to: '/admin', label: 'Admin' })
  }

  return base
})
</script>

<template>
  <header class="sticky top-0 z-30 border-b border-white/10 bg-ink/70 backdrop-blur-xl">
    <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-3 md:px-8">
      <NuxtLink to="/" class="flex items-center gap-2">
        <span class="rounded-lg bg-sand-300/90 px-2 py-1 text-sm font-semibold text-ink">CAB</span>
        <span class="hidden text-sm font-semibold tracking-wide text-slate-200 sm:inline">Colegio de Abogados</span>
      </NuxtLink>

      <nav class="flex items-center gap-1 md:gap-2">
        <NuxtLink
          v-for="item in links"
          :key="item.to"
          :to="item.to"
          class="rounded-lg px-3 py-2 text-xs font-medium text-slate-200 transition hover:bg-white/15 md:text-sm"
        >
          {{ item.label }}
        </NuxtLink>
      </nav>

      <div class="flex items-center gap-3">
        <p v-if="user" class="hidden text-xs text-slate-300 md:block">
          {{ user.email }}
        </p>
        <button
          v-if="user"
          type="button"
          class="rounded-lg border border-white/20 bg-white/10 px-3 py-2 text-xs font-medium text-white transition hover:bg-white/20"
          @click="logout"
        >
          Cerrar sesion
        </button>
      </div>
    </div>
  </header>
</template>
