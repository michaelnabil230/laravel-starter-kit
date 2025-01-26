import { router, usePage } from '@inertiajs/vue3';
import { App, Plugin } from 'vue';
import toasts from '../Stores/toasts';

export const toast: Plugin = {
    install(app: App) {
        router.on('finish', (event) => {
            const { props } = usePage();

            const toast = props.toast;
            if (toast) {
                toasts.add(toast.message, toast.type);
            }
        });
    },
};
