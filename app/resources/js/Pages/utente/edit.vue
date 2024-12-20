<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from "@/primevue/layout/AppLayout.vue";
import { ref, onMounted } from 'vue';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';

const props = defineProps({
  pageTitle: String,
  user: Object
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
                <div class="col-1 p-0 m-0">
                    <div class="flex flex-column align-items-start">
                        <Button label="Cerca" class="m-0" raised @click="changeUserData" />
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</app-layout>
</template>

<style scoped lang="scss"></style>
