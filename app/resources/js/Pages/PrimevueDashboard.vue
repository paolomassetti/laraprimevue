<script setup>
import AppLayout from "@/primevue/layout/AppLayout.vue";
import Dashboard  from "@/primevue/views/Dashboard.vue";
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  pageTitle: String,
});

</script>

<template>
<Head>
    <title>{{ pageTitle }}</title>
</Head>
<app-layout>
    <Dashboard />
</app-layout>
</template>

<style scoped lang="scss"></style>
