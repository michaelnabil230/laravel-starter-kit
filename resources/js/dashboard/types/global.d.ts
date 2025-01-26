import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import 'vue';
import { route as ziggyRoute } from 'ziggy-js';
import { DashboardApp } from '../app';
import mixin from '../mixins/index';
import { Loading } from '../Plugins/loading';
import { PageProps as AppPageProps } from './';
import { AppConfig, DashboardAppInterface } from './appConfig';

declare global {
    interface Window {
        axios: AxiosInstance;
        createDashboardApp: (config: AppConfig) => void;
        dashboardApp: DashboardAppInterface;
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

interface Modal {
    component: string;
    baseURL: string;
    redirectURL: string | null;
    props: Record<string, any>;
    key: string;
    nonce: string;
}
