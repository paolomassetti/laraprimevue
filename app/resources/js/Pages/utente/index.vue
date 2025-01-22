<script setup>
import { ref, onMounted, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3'
import { useToast } from 'primevue/usetoast';
import { router } from '@inertiajs/vue3';
import { useConfirm } from "primevue/useconfirm";
import axios from 'axios';
import ToggleButton from 'primevue/togglebutton';
import Button from 'primevue/button';
import AppLayout from "@/primevue/layout/AppLayout.vue";
import InputText from 'primevue/inputtext';
import IndexTable from '@/Components/indexTable.vue';
import Confirmation from '@/Components/Confirmation.vue';

//DataTables
const users = ref([])
const totalRecords = ref(0)
const currentPage = ref(1)
const rowsPerPage = ref(10)
const sortField = ref('created_at')
const sortOrder = ref(1)
const loading = ref(false)
const refreshKey = ref(0)
const size = ref({ label: 'Normal', value: 'small' })

//Filters
const name = ref(null)
const email = ref(null)
const created_at = ref(null)
const filtersVisibily = ref(false)

const props = defineProps({
  pageTitle: String,
});

const loadUsers = async () => {
    loading.value = true

    const formattedDate = formatDateForServer(created_at.value)

    const response = await axios.post('/utenti/search', {
        start: (currentPage.value - 1) * rowsPerPage.value,
        length: rowsPerPage.value,
        sortField: sortField.value,
        sortOrder: sortOrder.value,
        name: name.value,
        email: email.value,
        created_at: formattedDate
    });
    users.value = response.data.data
    totalRecords.value = response.data.recordsTotal
    loading.value = false
};

//Toast
let successMsg = ref('')
let errorMsg = ref('')
const toastShown = ref(false)
const toast = useToast()
const page = usePage()

//Confirm
const confirm = useConfirm()
let currentUrlDelete = ref('')

const requireConfirmation = (url) => {
    currentUrlDelete = url
    confirm.require({
        group: 'headless',
        header: 'Vuoi procedere?',
        message: 'L\'utente sarà eliminato',
    });
};

//Columns
const userColumns = [
    { field: 'name', header: 'Nome', sortable: true },
    { field: 'email', header: 'Email', sortable: true },
    { field: 'created_at', header: 'Data Creazione', sortable: true }
];

onMounted(() => {
    successMsg.value = page.props.flash.success || '';
    errorMsg.value = page.props.flash.error || '';

    watch(() => page.props.flash.success, (newVal) => {
        if (newVal && !toastShown.value) {
            toast.add({
                severity: 'success',
                summary: 'Congratulazioni!',
                detail: newVal,
                life: 3000
            });
            toastShown.value = true;
        }
    });

    watch(() => page.props.flash.error, (newVal) => {
        if (newVal && !toastShown.value) {
            toast.add({
                severity: 'error',
                summary: 'Errore',
                detail: newVal,
                life: 3000
            });
            toastShown.value = true;
        }
    });
    loadUsers()
});

watch(() => page.props.flash, () => {
    toastShown.value = false
}, { deep: true });

const handlePageChange = ({ page, rows }) => {
    rowsPerPage.value = rows
    currentPage.value = page
    loadUsers();
};

const handleSortChange = ({ sortField: field, sortOrder: order }) => {
    sortField.value = field
    sortOrder.value = order
    loadUsers();
};

const refreshData = () => {
    sortField.value = 'created_at'
    sortOrder.value = 1
    currentPage.value = 1
    refreshKey.value++
    name.value = ''
    email.value = ''
    created_at.value = ''
    loadUsers()
};

const applyFilters = () => {
    currentPage.value = 1
    loadUsers()
};

const formatDateForServer = (date) => {
    if (!date) return null
    let localDate = new Date(date)
    localDate.setMinutes(localDate.getMinutes() - localDate.getTimezoneOffset())
    console.log(localDate.toISOString().split("T")[0])
    return localDate.toISOString().split("T")[0];
};

const createUser = () => {
    router.visit('/utente/new');
};

</script>
<template>
<Head>
    <title>{{ pageTitle }}</title>
</Head>

<Toast position="center"/>

<Confirmation
    group="headless"
    :confirmation-url="currentUrlDelete"
    @refreshData="refreshData"
></Confirmation>

<app-layout>
    <transition name="slide-fade">
        <div v-show="filtersVisibily" class="grid">
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
                            <DatePicker v-model="created_at" id="created_at" dateFormat="dd/mm/yy" />
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
    </transition>

    <div class="grid">
        <div class="col-12">
            <div class="card">
                <IndexTable
                    :refresh-key="refreshKey"
                    :data="users"
                    :columns="userColumns"
                    :rows-per-page="rowsPerPage"
                    :total-records="totalRecords"
                    :loading="loading"
                    :sort-field="sortField"
                    :sort-order="sortOrder"
                    :size="size.value"
                    stripedRows
                    rowHover
                    responsiveLayout="scroll"
                    @updatePage="handlePageChange"
                    @updateSort="handleSortChange"
                    @refreshData="refreshData"
                >
                    <template #empty>
                        <p>Nessun utente trovato.</p>
                    </template>

                    <template #header>
                        <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                            <span class="text-xl text-900 font-bold">{{ props.pageTitle }}</span>
                            <div class="flex flex-wrap align-items-center justify-content-right gap-2">
                                <ToggleButton
                                    v-model="filtersVisibily"
                                    class="shadow-none filter-button"
                                    offIcon="pi pi-filter"
                                    onIcon="pi pi-filter-slash"
                                    active
                                />
                                <Button
                                    icon="pi pi-plus"
                                    class="add-user"
                                    severity="success"
                                    rounded
                                    raised
                                    v-tooltip.left="'Aggiungi utente'"
                                    @click="createUser"
                                />
                            </div>
                        </div>
                    </template>

                    <template
                        #actions="{ data }"
                        >
                        <Button
                            icon="pi pi-pencil"
                            class="action-button"
                            severity="warn"
                            v-tooltip.left="'Modifica utente'"
                            text rounded
                            @click="() => router.visit(data.url_edit)"
                        />
                        <Button
                            @click="requireConfirmation(data.url_delete)"
                            icon="pi pi-times"
                            class="action-button"
                            v-tooltip.left="'Elimina utente'"
                            severity="danger"
                            text rounded
                            aria-label="Elimina"
                        />
                    </template>

                </IndexTable>
            </div>
        </div>
    </div>
</app-layout>
</template>

<style scoped lang="scss">
    .slide-fade-enter-active, .slide-fade-leave-active {
        transition: all 0.3s ease;
    }
    .slide-fade-enter-from, .slide-fade-leave-to {
        opacity: 0;
        transform: translateY(-20px);
    }
    .slide-fade-enter-to, .slide-fade-leave-from {
        opacity: 1;
        transform: translateY(0);
    }
</style>
