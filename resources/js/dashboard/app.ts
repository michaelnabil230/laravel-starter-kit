import '@/dashboard/bootstrap';
import { initializeTheme } from '@/dashboard/composables/useAppearance';
import { eventHub, eventHubKey } from '@/dashboard/lib/event-hub';
import { shortcutController, shortcutControllerKey } from '@/dashboard/lib/shortcut-controller';
import mixins from '@/dashboard/mixins/index';
import { loading, modal, toast } from '@/dashboard/Plugins/index';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import '../../css/dashboard/app.css';

window.initialSharedData = window.initialSharedData || {};

const appName = mixins.methods.initialSharedData('appName');

createInertiaApp({
    title: (title: string): string => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>(['./Pages/**/*.vue'])),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(modal, { resolve: props.resolveComponent })
            .use(toast)
            .use(loading)
            .use(plugin)
            .use(ZiggyVue)
            .mixin(mixins)
            .provide(eventHubKey, eventHub)
            .provide(shortcutControllerKey, shortcutController)
            .mount(el);
    },
    progress: {
        showSpinner: true,
        color: '#0d9488',
    },
});

initializeTheme();
