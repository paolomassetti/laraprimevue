<script setup>
import { ref } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';

const props = defineProps({
    data: {
        type: Array,
        required: true
    },
    totalRecords: {
        type: Number,
        required: true
    },
    columns: {
        type: Array,
        required: true,
    },
    rowsPerPage: {
        type: Number,
        default: 10
    },
    loading: {
        type: Boolean,
        default: false
    },
    sortField: {
        type: String,
        default: null
    },
    sortOrder: {
        type: Number,
        default: 1
    },
    pageTitle: {
        type: String
    },
    refreshKey: {
        type: Number,
        default: 0
    },
    size: {
        type: String
    }
});

const emit = defineEmits([
    'updatePage',
    'updateSort',
    'refreshData'
]);

const onPage = (event) => {
    emit('updatePage', { page: event.page + 1, rows: event.rows });
};

const onSort = (event) => {
    emit('updateSort', { sortField: event.sortField, sortOrder: event.sortOrder });
};

</script>

<template>
    <DataTable
        :value="data"
        :paginator="true"
        :rows="rowsPerPage"
        :totalRecords="totalRecords"
        :lazy="true"
        :loading="loading"
        :sortField="sortField"
        :sortOrder="sortOrder"
        :size="size"
        @page="onPage"
        @sort="onSort"
        stripedRows
        rowHover
        responsiveLayout="scroll"
        :rowsPerPageOptions="[5, 10, 20, 50]"
    >
        <template #empty>
            <slot name="empty"> Nessun dato trovato. </slot>
        </template>

        <template #header>
            <div class="flex flex-wrap align-items-center justify-content-between gap-2 pt-0 pb-1">
                <span class="text-xl text-900 font-bold">{{ pageTitle }}</span>
                <div class="flex flex-wrap align-items-center justify-content-right gap-2">
                    <slot name="header"></slot>
                </div>
            </div>
        </template>

        <template #paginatorstart>
            <Button
                v-tooltip.bottom="'Reset'"
                type="button"
                icon="pi pi-refresh"
                text
                @click="$emit('refreshData')"
            />
        </template>

        <template #paginatorend></template>

        <Column
            v-for="col in columns"
            :key="col.field"
            :field="col.field"
            :header="col.header"
            :sortable="col.sortable"
        ></Column>

        <Column header="azioni">
            <template #body="slotProps">
                <slot name="actions" :data="slotProps.data"></slot>
            </template>
        </Column>

    </DataTable>
</template>
