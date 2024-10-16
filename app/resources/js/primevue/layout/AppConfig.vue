<script setup>
import Sidebar from 'primevue/sidebar';

import { ref } from 'vue';
import { useLayout } from '@/primevue/layout/composables/layout';

defineProps({
    simple: {
        type: Boolean,
        default: false
    }
});
const visible = ref(false);

const { changeThemeSettings, layoutConfig } = useLayout();

const onConfigButtonClick = () => {
    visible.value = !visible.value;
};
const onChangeTheme = (theme, mode) => {
    const elementId = 'theme-css';
    const linkElement = document.getElementById(elementId);
    const cloneLinkElement = linkElement.cloneNode(true);
    const newThemeUrl = linkElement.getAttribute('href').replace(layoutConfig.theme.value, theme);
    cloneLinkElement.setAttribute('id', elementId + '-clone');
    cloneLinkElement.setAttribute('href', newThemeUrl);
    cloneLinkElement.addEventListener('load', () => {
        linkElement.remove();
        cloneLinkElement.setAttribute('id', elementId);
        changeThemeSettings(theme, mode === 'dark');
    });
    linkElement.parentNode.insertBefore(cloneLinkElement, linkElement.nextSibling);
};

</script>

<template>
    <button class="layout-config-button p-link" type="button" @click="onConfigButtonClick()">
        <i class="pi pi-cog"></i>
    </button>

    <Sidebar v-model:visible="visible" position="right" :transitionOptions="'.3s cubic-bezier(0, 0, 0.2, 1)'" class="layout-config-sidebar w-20rem">

        <h5>PrimeOne Design - 2022</h5>
        <div class="grid">
            <div class="col-3">
                <button class="p-link w-2rem h-2rem" @click="onChangeTheme('lara-light-indigo', 'light')">
                    <img src="/layout/images/themes/lara-light-indigo.png" class="w-2rem h-2rem" alt="Lara Light Indigo" />
                </button>
            </div>
            <div class="col-3">
                <button class="p-link w-2rem h-2rem" @click="onChangeTheme('lara-light-blue', 'light')">
                    <img src="/layout/images/themes/lara-light-blue.png" class="w-2rem h-2rem" alt="Lara Light Blue" />
                </button>
            </div>
            <div class="col-3">
                <button class="p-link w-2rem h-2rem" @click="onChangeTheme('lara-dark-indigo', 'dark')">
                    <img src="/layout/images/themes/lara-dark-indigo.png" class="w-2rem h-2rem" alt="Lara Dark Indigo" />
                </button>
            </div>
            <div class="col-3">
                <button class="p-link w-2rem h-2rem" @click="onChangeTheme('lara-dark-blue', 'dark')">
                    <img src="/layout/images/themes/lara-dark-blue.png" class="w-2rem h-2rem" alt="Lara Dark Blue" />
                </button>
            </div>
        </div>
    </Sidebar>
</template>

<style lang="scss" scoped></style>
