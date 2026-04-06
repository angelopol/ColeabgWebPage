<script setup lang="ts">
const { user } = useAuth()
</script>

<template>
  <section class="relative min-h-[calc(100vh-68px)] overflow-hidden">
    <img
      src="/entrada.jpg"
      alt="Colegio de Abogados del Estado Carabobo"
      class="absolute inset-0 h-full w-full object-cover"
    >
    <div class="absolute inset-0 bg-gradient-to-b from-vino-900/55 via-vino-950/62 to-verde-900/68" />

    <div class="relative flex min-h-[calc(100vh-68px)] flex-col items-center justify-center px-4 text-center backdrop-brightness-90">
      <h1 class="text-4xl font-extrabold tracking-tight text-white md:text-6xl lg:text-7xl">
        COLEGIO DE ABOGADOS DEL
      </h1>
      <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-white md:text-6xl lg:text-7xl">
        ESTADO CARABOBO
      </h1>
      <p class="mt-8 max-w-2xl text-base text-vino-50 md:text-lg">
        Portal institucional para verificacion de solvencias y operaciones.
      </p>

      <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
        <NuxtLink to="/search" class="btn-primary">Consultar operaciones</NuxtLink>
        <NuxtLink :to="user ? '/dashboard' : '/auth/login'" class="btn-secondary">
          {{ user ? 'Ir al dashboard' : 'Ingresar al sistema' }}
        </NuxtLink>
      </div>
    </div>

  </section>
</template>
