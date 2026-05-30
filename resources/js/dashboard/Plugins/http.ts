import { http } from '@inertiajs/core';
import { Plugin } from 'vue';

export const httpRequest: Plugin = {
    install() {
        http.onRequest((config) => {
            return {
                ...config,
                headers: {
                    ...(config.headers ?? {}),
                    'Accept-Language': document.documentElement.lang,
                },
            };
        });
    },
};
