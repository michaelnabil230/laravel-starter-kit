import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'node:path';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/front/app.js', 'resources/js/dashboard/app.ts'],
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    build: {
        chunkSizeWarningLimit: 1600,
    },
    resolve: {
        alias: {
            'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
        },
    },
});
