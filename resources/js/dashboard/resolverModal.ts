import { ref } from 'vue';

const resolveCallback = ref<CallableFunction>();

export default {
    setResolveCallback: (callback: CallableFunction): void => {
        resolveCallback.value = callback;
    },
    resolve: (name: string) => resolveCallback.value!(name),
};
