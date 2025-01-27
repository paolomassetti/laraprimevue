<script setup>
import ConfirmDialog from 'primevue/confirmdialog';
import Button from 'primevue/button';
import { useForm } from '@inertiajs/vue3';

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

const form = useForm({});
const onDelete = () => {
    form.delete(props.confirmationUrl, {
        onSuccess: () => {
            emit('refreshData');
        }
    })
}

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
                <Button
                    label="Conferma"
                    severity="success"
                    rounded
                    @click="onDelete(), acceptCallback()"
                />
                <Button
                    class="shadow-none"
                    label="Annulla"
                    rounded
                    outlined
                    @click="rejectCallback"
                />
            </div>
        </div>
    </template>
</ConfirmDialog>
</template>
