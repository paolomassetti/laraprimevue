<script setup>
import { Head, useForm  } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AppLayout from "@/primevue/layout/AppLayout.vue";
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';

const props = defineProps({
  pageTitle: String,
  user: Object,
  backUrl: String,
});

const form = useForm({
  id: props.user.id,
  name: props.user.name,
  email: props.user.email,
});

const updateUserData = () => {
  form.put(`/utente/update/${props.user.id}`, {});
};

</script>

<template>
<Head>
    <title>{{ pageTitle }}</title>
</Head>
<app-layout>
    <div class="grid">
        <div class="col-12">
            <div class="card flex flex-1 flex-wrap align-items-end">
                <div class="col-3 p-0 mr-4">
                    <div class="flex flex-column gap-2">
                        <label for="name">Nome</label>
                        <InputText id="name" v-model="form.name" />
                    </div>
                </div>
                <div class="col-3 p-0 mr-4">
                    <div class="flex flex-column gap-2">
                        <label for="email">Email</label>
                        <InputText id="email" v-model="form.email" datatype="email" />
                    </div>
                </div>
                <div class="col-12 pl-0 pb-0 flex gap-2">
                    <Button label="Salva" severity="success" class="m-0" raised @click="updateUserData" />
                    <Link class="p-button m-0  p-button-raised" :href="backUrl">Indietro</Link>
                </div>
            </div>
        </div>
    </div>
</app-layout>
</template>

<style scoped lang="scss"></style>
