import toasts from '@/dashboard/Stores/toasts';
import { router, usePage } from '@inertiajs/vue3';
import { App, Plugin } from 'vue';

export const toast: Plugin = {
    install(app: App) {
        app.config.globalProperties.$toasts = toasts;

        router.on('finish', () => {
            const { props } = usePage();
            const toast = props.toast;

            if (toast) {
                app.config.globalProperties.$toasts.add(toast.message, toast.type);
            }
        });
    },
};
