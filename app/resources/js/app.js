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
import Ripple from 'primevue/ripple';
import StyleClass from 'primevue/styleclass';
import ConfirmDialog from 'primevue/confirmdialog';
import ConfirmPopup from 'primevue/confirmpopup';
import Dialog from 'primevue/dialog';
import Calendar from 'primevue/calendar';
import Button from 'primevue/button';

import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const italianLocale = {
    firstDayOfWeek: 1,
    dayNames: ["Domenica", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato"],
    dayNamesShort: ["dom", "lun", "mar", "mer", "gio", "ven", "sab"],
    dayNamesMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
    monthNames: ["gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
    monthNamesShort: ["gen", "feb", "mar", "apr", "mag", "giu", "lug", "ago", "set", "ott", "nov", "dic"],
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(PrimeVue, { ripple: true, locale: italianLocale })
            .use(ToastService)
            .use(DialogService)
            .use(ConfirmationService)
             .directive('tooltip', Tooltip)
             .directive('InputText', InputText)
             .directive('badge', BadgeDirective)
             .directive('ripple', Ripple)
             .directive('styleclass', StyleClass)
             .component('Toast', Toast)
             .component('ConfirmDialog', ConfirmDialog)
             .component('ConfirmPopup', ConfirmPopup)
             .component('Dialog', Dialog)
             .component('router-link', Link)
             .component('Calendar', Calendar)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
