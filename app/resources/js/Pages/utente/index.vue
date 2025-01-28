<script setup>
import { ref, onMounted, watch, reactive } from 'vue'
import { Head } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import { useToast } from 'primevue/usetoast'
import { router } from '@inertiajs/vue3'
import { useConfirm } from "primevue/useconfirm"
import axios from 'axios'
import ToggleButton from 'primevue/togglebutton'
import Button from 'primevue/button'
import AppLayout from "@/primevue/layout/AppLayout.vue"
import InputText from 'primevue/inputtext'
import IndexTable from '@/Components/indexTable.vue'
import Confirmation from '@/Components/Confirmation.vue'

//DataTables
const users = ref([])
const totalRecords = ref(0)
const currentPage = ref(1)
const rowsPerPage = ref(10)
const sortField = ref('created_at')
const sortOrder = ref(1)
const loading = ref(false)
const refreshKey = ref(0)
const size = ref({ value: 'small'})

//Filters
const name = ref(null)
const email = ref(null)
const created_at = ref(null)
const filtersVisibily = ref(false)

const isFilterActive = reactive({
    name: false,
    email: false,
    created_at: false
})

watch(name, (newValue, oldValue) => {
    isFilterActive.name = !!newValue
})

watch(email, (newValue, oldValue) => {
    isFilterActive.email = !!newValue;
})

watch(created_at, (newValue, oldValue) => {
    isFilterActive.created_at = !!newValue;
})

const props = defineProps({
  pageTitle: String,
})

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
    })
    users.value = response.data.data
    totalRecords.value = response.data.recordsTotal
    loading.value = false
}

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
    currentUrlDelete.value = url
    confirm.require({
        group: 'headless',
        header: 'Vuoi procedere?',
        message: 'L\'utente sarà eliminato',
    })

    document.activeElement.blur();
}

//Columns
const userColumns = [
    { field: 'name', header: 'Nome', sortable: true },
    { field: 'email', header: 'Email', sortable: true },
    { field: 'created_at', header: 'Data Creazione', sortable: true }
]

onMounted(() => {
    successMsg.value = page.props.flash.success || ''
    errorMsg.value = page.props.flash.error || ''

    watch(() => page.props.flash.success, (newVal) => {
        if (newVal && !toastShown.value) {
            toast.add({
                severity: 'success',
                summary: 'Congratulazioni!',
                detail: newVal,
                life: 3000
            })
            toastShown.value = true;
        }
    }, { immediate: true });

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
    }, { immediate: true });

    loadUsers()
});

watch(() => page.props.flash, () => {
    toastShown.value = false
}, { deep: true });

const handlePageChange = ({ page, rows }) => {
    rowsPerPage.value = rows
    currentPage.value = page
    loadUsers()
}

const handleSortChange = ({ sortField: field, sortOrder: order }) => {
    sortField.value = field
    sortOrder.value = order
    loadUsers()
}

const refreshData = () => {
    sortField.value = 'created_at'
    sortOrder.value = 1
    currentPage.value = 1
    refreshKey.value++
    name.value = ''
    email.value = ''
    created_at.value = null
    loadUsers()
}

const applyFilters = () => {
    currentPage.value = 1
    loadUsers()
}

const formatDateForServer = (date) => {
    if (!date) return null
    let localDate = new Date(date)
    localDate.setMinutes(localDate.getMinutes() - localDate.getTimezoneOffset())
    return localDate.toISOString().split("T")[0]
}

const createUser = () => {
    router.visit('/utente/new')
}

const exportData = async () => {
    try {
        const formattedDate = formatDateForServer(created_at.value)
        const response = await axios.post('/utenti/export', {
            name: name.value,
            email: email.value,
            created_at: formattedDate
        }, {
            responseType: 'blob'
        });

        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'utenti_export.xlsx');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

    } catch {
        toast.add({
            severity: 'success',
            summary: 'Errore!',
            detail: 'Errore durante l\'esportazione',
            life: 3000
        })
    }
}

</script>
<template>
<Head>
    <title>{{ pageTitle }}</title>
</Head>

<Toast />

<Confirmation
    group="headless"
    :confirmation-url="currentUrlDelete"
    @refreshData="refreshData"
></Confirmation>

<app-layout>
    <transition name="slide-fade">
        <div v-show="filtersVisibily" class="grid">
            <div class="col-12">
                <div class="card flex flex-1 align-items-end p-5">
                    <div class="col-3 p-0 mr-4">
                        <FloatLabel>
                            <div class="flex flex-column gap-2">
                                <label
                                    for="name"
                                    :class="{ 'text-indigo-500': isFilterActive.name }"
                                >
                                    Nome
                                </label>
                                <InputText
                                    id="name"
                                    v-model="name"
                                    :class="{ 'border border-indigo-500': isFilterActive.name }"
                                />
                            </div>
                        </FloatLabel>
                    </div>
                    <div class="col-3 p-0 mr-4">
                        <FloatLabel>
                            <div class="flex flex-column gap-2">
                                <label
                                    for="email"
                                    :class="{ 'text-indigo-500': isFilterActive.email }"
                                >
                                    Email
                                </label>
                                <InputText
                                    id="email"
                                    v-model="email"
                                    datatype="email"
                                    :class="{ 'border border-indigo-500': isFilterActive.email }"
                                />
                            </div>
                        </FloatLabel>
                    </div>
                    <div class="col-3 p-0 mr-4">
                        <FloatLabel>
                            <div class="flex flex-column gap-2">
                                <label
                                    for="created_at"
                                    :class="{ 'text-indigo-500': isFilterActive.created_at }"
                                >
                                    Data creazione
                                </label>
                                <DatePicker
                                    v-model="created_at"
                                    id="created_at"
                                    class="p-0 shadow-none"
                                    dateFormat="dd/mm/yy"
                                    :class="{ 'border border-indigo-500': isFilterActive.created_at }"
                                />
                            </div>
                        </FloatLabel>
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
                    :page-title="pageTitle"
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
                        <Button
                            icon="pi pi-external-link"
                            class="add-user"
                            rounded
                            raised
                            v-tooltip.left="'Esporta'"
                            @click="exportData"
                        />
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

<style scoped lang="scss"></style>
