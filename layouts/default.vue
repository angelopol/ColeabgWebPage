<script setup lang="ts">
const route = useRoute()
const isHome = computed(() => route.path === '/')
</script>

<template>
  <div :class="isHome ? 'relative min-h-screen' : 'relative min-h-screen bg-halo'">
    <AppLegacyAmbient v-if="!isHome" />
    <div
      v-if="!isHome"
      class="pointer-events-none absolute inset-0 z-10 bg-[radial-gradient(circle_at_80%_-20%,rgba(255,255,255,0.14),transparent_40%)]"
    />
    <AppNavBar />

    <main
      :key="route.path"
      :class="isHome ? 'relative z-20 w-full p-0' : 'relative z-20 mx-auto w-full max-w-7xl px-4 py-8 md:px-8 md:py-10'"
    >
      <slot />
    </main>
  </div>
</template>
