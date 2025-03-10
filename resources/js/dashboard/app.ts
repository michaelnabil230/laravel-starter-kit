import { initializeTheme } from '@/dashboard/composables/useAppearance';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import Mousetrap, { ExtendedKeyboardEvent } from 'mousetrap';
import 'mousetrap/plugins/pause/mousetrap-pause.js';
import { TinyEmitter } from 'tiny-emitter';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../../vendor/tightenco/ziggy';
import '../../css/dashboard/app.css';
import './bootstrap';
import mixins from './mixins/index';
import { loading, modal, queryStringUpdater, toast } from './plugins/index';
import toasts from './stores/toasts';
import { AppConfig, DashboardAppInterface } from './types/appConfig';
import { Toast } from './types/toast';

window.createDashboardApp = (config: AppConfig) => {
    const dashboardApp = new DashboardApp(config);

    dashboardApp.run();

    window.dashboardApp = dashboardApp;
};

export class DashboardApp implements DashboardAppInterface {
    private appConfig: AppConfig;

    private emitter: TinyEmitter;

    constructor(config: AppConfig) {
        this.appConfig = config;
        this.emitter = new TinyEmitter();
    }

    run(): void {
        this.log('Initiating Dashboard App is running...');

        const appName = this.config('appName');

        createInertiaApp({
            title: (title: string) => `${title} - ${appName}`,
            resolve: (name) =>
                resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>(['./pages/**/*.vue'])),
            setup({ el, App, props, plugin }) {
                createApp({ render: () => h(App, props) })
                    .use(modal, { resolve: props.resolveComponent })
                    .use(toast)
                    .use(loading)
                    .use(queryStringUpdater)
                    .use(plugin)
                    .use(ZiggyVue)
                    .mixin(mixins)
                    .mount(el);
            },
            progress: {
                showSpinner: true,
                color: '#0d9488',
            },
        });

        initializeTheme();
    }

    public log(message: string, type: 'log' | 'error' | 'warn' | 'info' = 'log'): void {
        console[type](`[AppDashboard]`, message);
    }

    public $on(event: string, listener: (...args: any[]) => void): void {
        this.emitter.on(event, listener);
    }

    public $once(event: string, listener: (...args: any[]) => void): void {
        this.emitter.once(event, listener);
    }

    public $off(event: string, listener: (...args: any[]) => void): void {
        this.emitter.off(event, listener);
    }

    public $emit(event: string, ...args: any[]): void {
        this.emitter.emit(event, ...args);
    }

    public config<T>(key: keyof AppConfig): T {
        return this.appConfig[key] as T;
    }

    public add(message: string, type: Toast['type'] = 'info'): void {
        toasts.add(message, type);
    }

    public success(message: string): void {
        toasts.success(message);
    }

    public danger(message: string): void {
        toasts.danger(message);
    }

    public info(message: string): void {
        toasts.info(message);
    }

    public warning(message: string): void {
        toasts.warning(message);
    }

    public remove(id: string): void {
        toasts.remove(id);
    }

    public clear(): void {
        toasts.clear();
    }

    public addShortcut(
        keys: string | string[],
        callback: (e: ExtendedKeyboardEvent, combo: string) => boolean | void,
    ): void {
        Mousetrap.bind(keys, callback);
    }

    public disableShortcut(keys: string | string[]): void {
        Mousetrap.unbind(keys);
    }

    public pauseShortcuts(): void {
        Mousetrap.pause();
    }

    public resumeShortcuts(): void {
        Mousetrap.unpause();
    }
}
