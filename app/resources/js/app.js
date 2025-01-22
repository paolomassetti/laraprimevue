import './bootstrap';
import '../css/app.css';
import PrimeVue from 'primevue/config';
import '@/primevue/assets/styles.scss';
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';
import DialogService from 'primevue/dialogservice';
import ConfirmationService from 'primevue/confirmationservice';
import BadgeDirective from 'primevue/badgedirective';
import Tooltip from 'primevue/tooltip';
import InputText from 'primevue/inputtext';
import StyleClass from 'primevue/styleclass';
import ConfirmDialog from 'primevue/confirmdialog';
import ConfirmPopup from 'primevue/confirmpopup';
import Dialog from 'primevue/dialog';
import DatePicker from 'primevue/datepicker';
import axios from 'axios';
import FloatLabel from 'primevue/floatlabel';
import { definePreset } from '@primevue/themes';
import Aura from '@primevue/themes/aura';

import { router } from '@inertiajs/vue3';
import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = import.meta.env.VITE_APP_NAME || 'PrimeVue';

axios.interceptors.response.use(response => {
    return response;
}, error => {
    if (error.response && (error.response.status === 401 || error.response.status === 419)) {
        router.push('/login');
    }
    return Promise.reject(error);
});

const italianLocale = {
    firstDayOfWeek: 1,
    dayNames: ["Domenica", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato"],
    dayNamesShort: ["dom", "lun", "mar", "mer", "gio", "ven", "sab"],
    dayNamesMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
    monthNames: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
    monthNamesShort: ["gen", "feb", "mar", "apr", "mag", "giu", "lug", "ago", "set", "ott", "nov", "dic"],
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(PrimeVue, {
                locale: italianLocale,
                theme: {
                    preset: definePreset(Aura, {
                        semantic: {
                            primary: {
                            50: "{indigo.50}",
                            100: "{indigo.100}",
                            200: "{indigo.200}",
                            300: "{indigo.300}",
                            400: "{indigo.400}",
                            500: "{indigo.500}",
                            600: "{indigo.600}",
                            700: "{indigo.700}",
                            800: "{indigo.800}",
                            900: "{indigo.900}",
                            950: "{indigo.950}"
                            }
                        }
                    })
                }
            })
            .use(ToastService)
            .use(DialogService)
            .use(ConfirmationService)
             .directive('tooltip', Tooltip)
             .directive('InputText', InputText)
             .directive('badge', BadgeDirective)
             .directive('styleclass', StyleClass)
             .component('Toast', Toast)
             .component('ConfirmDialog', ConfirmDialog)
             .component('ConfirmPopup', ConfirmPopup)
             .component('Dialog', Dialog)
             .component('router-link', Link)
             .component('DatePicker', DatePicker)
             .component('FloatLabel', FloatLabel)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
