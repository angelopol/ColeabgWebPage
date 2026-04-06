<script setup lang="ts">
const route = useRoute()

const installManifestHref = computed(() =>
  route.path.startsWith('/support') ? '/manifest-support.json' : '/manifest-search.json'
)

useHead(() => ({
  link: [
    {
      key: 'app-manifest',
      rel: 'manifest',
      href: installManifestHref.value
    }
  ]
}))
</script>

<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>
