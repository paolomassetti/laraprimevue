<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from "@/primevue/layout/AppLayout.vue";
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';

const props = defineProps({
  pageTitle: String,
});

// Form Data
const form = useForm({
  name: '',
  email: '',
});

const storeUserData = () => {
  form.post(`/utente/store`, {});
};

</script>

<template>
<Head>
    <title>{{ pageTitle }}</title>
</Head>
<app-layout>
    <form @submit.prevent="storeUserData" class="grid">
        <div class="col-12">
            <div class="card flex flex-1 align-items-end">
                <div class="col-3 p-0 mr-4">
                    <FloatLabel>
                        <div class="flex flex-column gap-2">
                            <label for="name">Nome*</label>
                            <InputText id="name" v-model="form.name" :invalid="form.errors.name" />
                        </div>
                    </FloatLabel>
                </div>
                <div class="col-3 p-0 mr-4">
                    <FloatLabel>
                        <div class="flex flex-column gap-2">
                            <label for="email">Email*</label>
                            <InputText id="email" v-model="form.email" type="email" :invalid="form.errors.email" />
                        </div>
                    </FloatLabel>
                </div>
                <div class="col-1 p-0 m-0">
                    <div class="flex flex-column align-items-start">
                        <Button type="submit" label="Salva" severity="success" class="m-0" raised />
                    </div>
                </div>
            </div>
        </div>
    </form>
</app-layout>
</template>

<style scoped lang="scss"></style>
