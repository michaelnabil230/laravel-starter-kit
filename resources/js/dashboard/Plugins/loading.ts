import { router } from '@inertiajs/vue3';
import { App, Plugin, reactive } from 'vue';

export type Loading = {
    is: boolean;
    start: () => void;
    end: () => void;
};

export const loading: Plugin = {
    install(app: App) {
        app.config.globalProperties.$loading = reactive<Loading>({
            is: false,
            start: () => {
                app.config.globalProperties.$loading.is = true;
            },
            end: () => {
                app.config.globalProperties.$loading.is = false;
            },
        });

        router.on('start', () => app.config.globalProperties.$loading.start());
        router.on('finish', () => app.config.globalProperties.$loading.end());
    },
};
