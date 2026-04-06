<script setup lang="ts">
const route = useRoute()
</script>

<template>
  <div class="relative min-h-screen bg-halo">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_80%_-20%,rgba(255,255,255,0.14),transparent_40%)]" />
    <AppNavBar />

    <main :key="route.path" class="relative mx-auto w-full max-w-7xl px-4 py-8 md:px-8 md:py-10">
      <slot />
    </main>
  </div>
</template>
