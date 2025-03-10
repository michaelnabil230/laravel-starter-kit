import { router, usePage } from '@inertiajs/vue3';
import { Plugin } from 'vue';
import toasts from '../stores/toasts';

export const toast: Plugin = {
    install() {
        router.on('finish', () => {
            const { props } = usePage();

            const toast = props.toast;
            if (toast) {
                toasts.add(toast.message, toast.type);
            }
        });
    },
};
