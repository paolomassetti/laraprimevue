<script setup>
import AppLayout from "@/primevue/layout/AppLayout.vue";
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';

const props = defineProps({
  pageTitle: String,
});

</script>

<template>
<Head>
    <title>{{ pageTitle }}</title>
</Head>
<app-layout>
    <div class="grid">
        <div class="col-12">
            <div class="card"></div>
        </div>
    </div>
</app-layout>
</template>

<style scoped lang="scss"></style>
