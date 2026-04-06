<script setup lang="ts">
const { user } = useAuth()

const modules = [
  {
    title: 'Consulta de Operaciones',
    description: 'Busqueda por cedula o Inpre con historial reciente y detalle anual.',
    to: '/search'
  },
  {
    title: 'Area de Afiliados',
    description: 'Acceso al dashboard personal, operaciones y cambio de contrasena.',
    to: '/auth/login'
  },
  {
    title: 'Control Administrativo',
    description: 'Panel de incidencias, duplicados y registro de solvencias.',
    to: '/admin'
  },
  {
    title: 'Asistencia de Trabajadores',
    description: 'Registro de entrada y salida con validacion por geolocalizacion.',
    to: '/workers'
  }
]
</script>

<template>
  <section class="grid gap-8 lg:grid-cols-[1.2fr,0.8fr]">
    <div class="panel relative overflow-hidden">
      <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-sand-300/25 blur-3xl" />
      <p class="mb-3 text-sm uppercase tracking-[0.25em] text-sand-200">Plataforma Unificada</p>
      <h1 class="max-w-2xl text-4xl leading-tight text-white md:text-6xl">
        Colegio de Abogados del Estado Carabobo
      </h1>
      <p class="mt-5 max-w-xl text-sm leading-relaxed text-slate-200 md:text-base">
        Migracion completa a Nuxt full-stack: frontend en Vue + Tailwind y backend API para autenticacion,
        operaciones, solvencias y asistencia.
      </p>

      <div class="mt-7 flex flex-wrap gap-3">
        <NuxtLink to="/search" class="btn-primary">Consultar operaciones</NuxtLink>
        <NuxtLink
          :to="user ? '/dashboard' : '/auth/login'"
          class="btn-secondary"
        >
          {{ user ? 'Ir al dashboard' : 'Ingresar al sistema' }}
        </NuxtLink>
      </div>
    </div>

    <div class="grid gap-4">
      <NuxtLink
        v-for="item in modules"
        :key="item.title"
        :to="item.to"
        class="panel block transition hover:-translate-y-0.5 hover:border-sand-300/40"
      >
        <h2 class="text-2xl text-sand-100">{{ item.title }}</h2>
        <p class="mt-2 text-sm text-slate-200">{{ item.description }}</p>
      </NuxtLink>
    </div>
  </section>
</template>
