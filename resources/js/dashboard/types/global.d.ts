import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import 'vue';
import { route as ziggyRoute } from 'ziggy-js';
import mixin from '../mixins/index';
import { Loading } from '../Plugins/loading';
import { PageProps as AppPageProps } from './';
import { SharedData } from './shared-data';

declare global {
    interface Window {
        axios: AxiosInstance;
        initialSharedData: SharedData;
    }

    const axios: AxiosInstance;
    const route: typeof ziggyRoute;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
        __: typeof mixin.methods.__;
        date: typeof mixin.methods.date;
        initialSharedData: typeof mixin.methods.initialSharedData;
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
