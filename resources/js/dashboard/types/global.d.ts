import { PageProps as InertiaPageProps, Page } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import dropzone from 'dropzone';
import 'vue';
import { route as ziggyRoute } from 'ziggy-js';
import mixin from '../mixins/index';
import { Loading } from '../Plugins/loading';
import { PageProps as AppPageProps } from './';
import { AppConfig, DashboardAppInterface } from './appConfig';

declare global {
    interface Window {
        axios: AxiosInstance;
        createDashboardApp: (config: AppConfig) => void;
        dashboardApp: DashboardAppInterface;
        dropzone: typeof dropzone;
    }

    const axios: AxiosInstance;
    const dashboardApp: DashboardAppInterface;
    const route: typeof ziggyRoute;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        dashboardApp: DashboardApp;
        route: typeof ziggyRoute;
        __: typeof mixin.methods.__;
        date: typeof mixin.methods.date;
        config: typeof mixin.methods.config;
        truncate: typeof mixin.methods.truncate;
        $loading: Loading;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

declare module '@inertiajs/vue3' {
    export declare function usePage(): Page<{ modal: Modal }>;
}

interface Modal {
    component: string;
    baseURL: string;
    redirectURL: string | null;
    props: Record<string, any>;
    key: string;
    nonce: string;
}
