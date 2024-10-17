<script setup>
import AppLayout from "@/primevue/layout/AppLayout.vue";
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';

const users = ref([]);
const sortField = ref('name');
const sortOrder = ref(1);
const refreshKey = ref(0);
const loading = ref(true);

const loadUsers = async () => {
    const response = await axios.get('/api/users');
    users.value = response.data;
    loading.value = false;
}

onMounted(loadUsers);

const formatDate = (value) => {
    const date = new Date(value);
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
};

const refreshData = () => {
    sortField.value = 'name';
    sortOrder.value = 1;
    refreshKey.value++;
    loadUsers();
};


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
            <div class="card">
                <DataTable
                    :key="refreshKey"
                    :sortField="sortField"
                    :sortOrder="sortOrder"
                    :value="users"
                    :paginator="true"
                    stripedRows
                    :rows="10"
                    dataKey="id"
                    :rowHover="true"
                    responsiveLayout="scroll"
                    :rowsPerPageOptions="[5, 10, 20, 50]"
                    :globalFilterFields="['name','email', 'created_at']"
                >
                    <template #empty> Nessun utente trovato. </template>
                    <template #loading> Loading customers data. Please wait. </template>
                    <template #paginatorstart>
                        <Button type="button" icon="pi pi-refresh" @click="refreshData" text />
                    </template>
                    <template #paginatorend></template>
                    <Column field="name" header="Name" sortable :style="{ width: '33%' }">
                        <template #body="{ data }">
                            {{ data.name }}
                        </template>
                    </Column>
                    <Column header="Email" field="email" sortable :style="{ width: '33%' }">
                        <template #body="{ data }">
                            {{ data.email }}
                        </template>
                    </Column>
                    <Column header="Data creazione" field="created_at" dataType="date" :style="{ width: '33%' }">
                        <template #body="{ data }">
                            {{ formatDate(data.created_at) }}
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </div>
</app-layout>
</template>

<style scoped lang="scss"></style>
