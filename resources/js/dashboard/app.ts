import '@/dashboard/bootstrap';
import { initializeTheme } from '@/dashboard/composables/useAppearance';
import { eventHub, eventHubKey } from '@/dashboard/lib/event-hub';
import { shortcutController, shortcutControllerKey } from '@/dashboard/lib/shortcut-controller';
import mixins from '@/dashboard/mixins/index';
import plugins from '@/dashboard/Plugins/index';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createPinia } from 'pinia';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import '../../css/dashboard/app.css';

window.initialSharedData = window.initialSharedData || {};

const appName = mixins.methods.initialSharedData('appName');

const pinia = createPinia();

createInertiaApp({
    title: (title: string): string => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>(['./Pages/**/*.vue'])),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugins.modal, props.resolveComponent)
            .use(plugins.toast)
            .use(plugins.loading)
            .use(plugins.day)
            .use(plugin)
            .use(pinia)
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
