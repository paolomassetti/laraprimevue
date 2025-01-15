<script setup>
import { Link } from '@inertiajs/vue3';
import ConfirmDialog from 'primevue/confirmdialog';
import Button from 'primevue/button';

const props = defineProps({
    group: {
        type: String,
        required: true,
        default: 'headless'
    },
    confirmationUrl: {
        type: String,
        required: true,
        default: ''
    }
})

const emit = defineEmits([
    'refreshData',
])


</script>

<template>
<ConfirmDialog :group="group">
    <template #container="{ message, rejectCallback, acceptCallback }">
        <div class="flex flex-column align-items-center p-5 surface-overlay border-round">
            <div class="border-circle bg-primary inline-flex justify-content-center align-items-center h-6rem w-6rem -mt-8">
                <i class="pi pi-question text-5xl"></i>
            </div>
            <span class="font-bold text-2xl block mb-2 mt-4">{{ message.header }}</span>
            <p class="mb-0">{{ message.message }}</p>
            <div class="flex align-items-center gap-2 mt-4">
                <Link
                    :href="confirmationUrl"
                    as="button"
                    class="delete-button"
                    method="delete"
                    @success="() => {
                        acceptCallback()
                        emit('refreshData')
                    }
                ">
                    <Button
                        label="Conferma"
                        severity="success"
                        rounded
                    />
                </Link>
                <Button
                    class="shadow-none"
                    label="Annulla"
                    rounded
                    outlined
                    @click="rejectCallback"
                >
                </Button>
            </div>
        </div>
    </template>
</ConfirmDialog>
</template>
