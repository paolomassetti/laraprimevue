<script setup>
import { computed, ref, onMounted } from 'vue';
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { Head } from '@inertiajs/vue3';
import AppLayout from "@/primevue/layout/AppLayout.vue";
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3'
import { useToast } from 'primevue/usetoast';

//DataTables
const users = ref([]);
const totalRecords = ref(0);
const currentPage = ref(1);
const rowsPerPage = ref(10);
const sortField = ref('name');
const sortOrder = ref(1);
const loading = ref(false);
const refreshKey = ref(0);

//Toast
let successMsg = ref(null);

//Filters
const name = ref(null);
const email = ref(null);
const created_at = ref(null);

const props = defineProps({
  pageTitle: String,
});

const loadUsers = async () => {
    loading.value = true;

    const formattedDate = formatDateForServer(created_at.value);

    const response = await axios.post('/utenti/search', {
        start: (currentPage.value - 1) * rowsPerPage.value,
        length: rowsPerPage.value,
        sortField: sortField.value,
        sortOrder: sortOrder.value,
        name: name.value,
        email: email.value,
        created_at: formattedDate

    });
    users.value = response.data.data;
    totalRecords.value = response.data.recordsTotal;
    loading.value = false;
};

const toast = useToast();
const page = usePage();
successMsg = computed(() => page.props.flash.success);

onMounted(() => {
    if (page.props.flash.success) {
        toast.add({
            severity: 'success',
            summary: 'Congratulazioni!',
            detail: successMsg,
            life: 3000
        });
    }

    loadUsers();
});

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
    name.value = '';
    email.value = '';
    created_at.value = '';
    loadUsers();
};

const applyFilters = () => {
    currentPage.value = 1;
    loadUsers();
};

const formatDateForServer = (date) => {
    if (!date) return null;
    let localDate = new Date(date);
    localDate.setMinutes(localDate.getMinutes() - localDate.getTimezoneOffset());
    console.log(localDate.toISOString().split("T")[0])
    return localDate.toISOString().split("T")[0];
};

</script>
<template>
<Head>
    <title>{{ pageTitle }}</title>
</Head>
<Toast />
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
                        <label for="created_at">Data creazione</label>
                        <Calendar v-model="created_at" id="created_at" dateFormat="dd/mm/yy" />
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
                    <Column field="created_at" header="Data creazione" sortable />
                    <Column field="azioni" style="width: 10%; min-width: 8rem" bodyStyle="text-align:center">
                        <template #body="slotProps">
                            <Link :href="slotProps.data.url_edit">
                                <Button icon="pi pi-pencil" severity="warning" text rounded aria-label="Modifica"/>
                            </Link>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </div>
</app-layout>
</template>

<style scoped lang="scss">
.p-button.p-button-icon-only {
    width: 2rem;
    height: fit-content;
    padding: 0;
    &:hover, &:focus, &:active {
        background-color: transparent;
        box-shadow: none;
    }
}
</style>
