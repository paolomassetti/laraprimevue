<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { Head } from '@inertiajs/vue3';
import AppLayout from "@/primevue/layout/AppLayout.vue";
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';


//DataTables
const users = ref([]);
const totalRecords = ref(0);
const currentPage = ref(1);
const rowsPerPage = ref(10);
const sortField = ref('name');
const sortOrder = ref(1);
const loading = ref(false);
const refreshKey = ref(0);

//Filters
const name = ref(null);
const email = ref(null);
const created_at = ref(null);

const props = defineProps({
  pageTitle: String,
});

const loadUsers = async () => {
    loading.value = true;

    const response = await axios.post('/utenti/search', {
        start: (currentPage.value - 1) * rowsPerPage.value,
        length: rowsPerPage.value,
        sortField: sortField.value,
        sortOrder: sortOrder.value,
        name: name.value,
        email: email.value
    });
    users.value = response.data.data;
    totalRecords.value = response.data.recordsTotal;
    loading.value = false;
};

onMounted(loadUsers);

const onPage = event => {
    currentPage.value = event.page + 1;
    rowsPerPage.value = event.rows;
    loadUsers();
};

const onSort = event => {
    sortField.value = event.sortField;
    sortOrder.value = event.sortOrder;
    loadUsers();
};

const refreshData = () => {
    sortField.value = 'name';
    sortOrder.value = 1;
    currentPage.value = 1;
    refreshKey.value++;
    loadUsers();
};

const applyFilters = () => {
    currentPage.value = 1;
    loadUsers();
};


</script>

<template>
<Head>
    <title>{{ pageTitle }}</title>
</Head>
<app-layout>
    <div class="grid">
        <div class="col-12">
            <div class="card flex flex-1 align-items-end">
                <div class="col-3 p-0 mr-4">
                    <div class="flex flex-column gap-2">
                        <label for="name">Nome</label>
                        <InputText id="name" v-model="name" />
                    </div>
                </div>
                <div class="col-3 p-0 mr-4">
                    <div class="flex flex-column gap-2">
                        <label for="email">Email</label>
                        <InputText id="email" v-model="email" datatype="email" />
                    </div>
                </div>
                <div class="col-3 p-0 mr-4">
                    <div class="flex flex-column gap-2">
                        <label for="email">Data creazione</label>
                        <InputText id="created_at" v-model="created_at" datatype="date" />
                    </div>
                </div>
                <div class="col-1 p-0 m-0">
                    <div class="flex flex-column align-items-start">
                        <Button label="Cerca" class="m-0" raised @click="applyFilters" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <DataTable
                    :key="refreshKey"
                    :value="users"
                    :paginator="true"
                    :rows="rowsPerPage"
                    :totalRecords="totalRecords"
                    :lazy="true"
                    :loading="loading"
                    @page="onPage"
                    @sort="onSort"
                    :sortField="sortField"
                    :sortOrder="sortOrder"
                    stripedRows
                    rowHover
                    responsiveLayout="scroll"
                    :rowsPerPageOptions="[5, 10, 20, 50]"
                >
                    <template #empty> Nessun utente trovato. </template>
                    <template #paginatorstart>
                        <Button v-tooltip.bottom="'Ricarica tabella'" type="button" icon="pi pi-refresh" @click="refreshData" text />
                    </template>
                    <template #paginatorend></template>
                    <Column field="name" header="Name" sortable />
                    <Column field="email" header="Email" sortable />
                    <Column field="created_at" header="Created At" sortable />
                </DataTable>
            </div>
        </div>
    </div>
</app-layout>
</template>

<style scoped lang="scss"></style>
