import { App, Plugin, ref } from 'vue';

const resolveCallback = ref<CallableFunction>();

export const modal: Plugin = {
    install(app: App, resolve: (name: string) => void) {
        resolveCallback.value = resolve;
    },
};

export const resolverModal = (name: string) => resolveCallback.value!(name);
