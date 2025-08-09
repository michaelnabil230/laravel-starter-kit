import { resolverModal } from '@/dashboard/Plugins/modal';
import { router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, defineAsyncComponent, h, nextTick, Ref, ref, shallowRef, VNode, watch } from 'vue';

export interface Modal {
    show: Ref<boolean>;
    vnode: Ref<VNode | null>;
    close: () => void;
}

export default function useModal(): Modal {
    const page = usePage();
    const modal = computed(() => page?.props?.modal);
    const key = computed(() => modal.value?.key);

    const componentName = ref();
    const component = shallowRef();
    const show = ref(false);
    const vnode = ref();
    const nonce = ref();

    const applyHeaders = (headers: Record<string, string | null>) => {
        Object.entries(headers).forEach(([headerKey, value]) => {
            ['post', 'put', 'patch', 'delete'].forEach((method) => {
                // @ts-expect-error @ts-ignore
                axios.defaults.headers[method][headerKey] = value;
            });
        });
    };

    const clearHeaders = () => {
        ['X-Inertia-Modal-Key', 'X-Inertia-Modal-Redirect'].forEach((header) => {
            ['get', 'post', 'put', 'patch', 'delete'].forEach((method) => {
                // @ts-expect-error @ts-ignore
                delete axios.defaults.headers[method][header];
            });
        });
    };

    const syncHeaders = () => {
        const redirectURL = modal.value?.redirectURL;
        applyHeaders({
            'X-Inertia-Modal-Key': key.value,
            'X-Inertia-Modal-Redirect': redirectURL,
        });
        axios.defaults.headers.get['X-Inertia-Modal-Redirect'] = redirectURL || '';
    };

    const close = () => {
        const redirectURL = modal.value?.redirectURL || modal.value?.baseURL;

        // Reset modal state
        show.value = false;
        vnode.value = null;
        nonce.value = null;
        clearHeaders();

        // Redirect if a URL is provided
        if (redirectURL) {
            router.visit(redirectURL, {
                preserveScroll: true,
                preserveState: true,
            });
        }
    };

    const resolveComponent = () => {
        if (nonce.value === modal.value?.nonce || !modal.value?.component) {
            return close();
        }

        if (componentName.value !== modal.value?.component) {
            componentName.value = modal.value.component;
            component.value = componentName.value
                ? defineAsyncComponent(() => resolverModal(componentName.value))
                : null;
        }

        nonce.value = modal.value?.nonce;
        vnode.value = component.value
            ? h(component.value, {
                  key: key.value,
                  ...modal.value.props,
              })
            : null;

        nextTick(() => (show.value = true));
    };

    resolveComponent();

    watch(
        modal,
        () => {
            if (modal.value?.nonce !== nonce.value) {
                resolveComponent();
            }
        },
        { deep: true },
    );

    watch(key, syncHeaders);

    return {
        show,
        vnode,
        close,
    };
}
