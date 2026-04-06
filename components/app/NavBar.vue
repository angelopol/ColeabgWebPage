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
      { to: '/search', label: 'Consulta' }
    ]
  }

  const base = [
    { to: '/dashboard', label: 'Dashboard' },
    { to: '/search', label: 'Buscador' },
    { to: '/workers', label: 'Asistencia' }
  ]

  if (user.value.role === 'admin') {
    base.push({ to: '/admin', label: 'Admin' })
  }

  return base
})
</script>

<template>
  <header class="sticky top-0 z-30 border-b border-vino-200/30 bg-vino-950/75 backdrop-blur-xl">
    <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-3 md:px-8">
      <NuxtLink to="/" class="flex items-center gap-2">
        <span class="rounded-lg bg-vino-500 px-2 py-1 text-sm font-semibold text-white">CAB</span>
        <span class="hidden text-sm font-semibold tracking-wide text-vino-100 sm:inline">Colegio de Abogados</span>
      </NuxtLink>

      <nav class="flex items-center gap-1 md:gap-2">
        <NuxtLink
          v-for="item in links"
          :key="item.to"
          :to="item.to"
          class="rounded-lg px-3 py-2 text-xs font-medium text-vino-100 transition hover:bg-verde-500/30 md:text-sm"
        >
          {{ item.label }}
        </NuxtLink>
      </nav>

      <div class="flex items-center gap-3">
        <p v-if="user" class="hidden text-xs text-vino-200 md:block">
          {{ user.email }}
        </p>
        <button
          v-if="user"
          type="button"
          class="rounded-lg border border-verde-300/40 bg-verde-700/40 px-3 py-2 text-xs font-medium text-verde-50 transition hover:bg-verde-600/55"
          @click="logout"
        >
          Cerrar sesion
        </button>
      </div>
    </div>
  </header>
</template>
