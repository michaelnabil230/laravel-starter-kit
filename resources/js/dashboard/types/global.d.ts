import { AppPageProps } from '@/dashboard/types/index';
import { PageProps as InertiaPageProps } from '@inertiajs/core';
import dayjs from 'dayjs';
import { route as ziggyRoute } from 'ziggy-js';
import { ContentVariants } from '../Components/Modal/Index';
import mixin from '../mixins/index';
import { SharedData } from './shared-data';

declare global {
    const route: typeof ziggyRoute;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
        __: typeof mixin.methods.__;
        date: typeof mixin.methods.date;
        truncate: typeof mixin.methods.truncate;
        $day: typeof dayjs;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps, SharedData {}
}

export interface Modal {
    id?: string;
    component: string;
    props: Record<string, unknown>;
    url: string;
    version: string;
    meta: {
        deferredProps?: Record<string, string[]>;
    };
    baseUrl: string;
    config: ModalConfig;
}

export interface ModalConfig {
    type?: 'modal' | 'slideover';
    size?: ContentVariants['size'];
    position?: ContentVariants['position'];
}
