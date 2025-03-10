import resolver from '@/dashboard/resolverModal';
import { App, Plugin } from 'vue';

export type ModalPluginOptions = {
    resolve: (name: string) => void;
};

export const modal: Plugin = {
    install(app: App, options: ModalPluginOptions) {
        resolver.setResolveCallback(options.resolve);
    },
};
