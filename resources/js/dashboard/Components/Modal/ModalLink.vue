<script setup lang="ts">
import { only as onlyKeys, rejectNullValues } from '@/dashboard/composables/modal/helpers';
import { modalPropNames, prefetch as prefetchModal, useModalStack } from '@/dashboard/composables/modal/modalStack';
import { modalContextKey } from '@/dashboard/composables/useModal';
import type { Method, RequestPayload } from '@inertiajs/core';
import { computed, onBeforeUnmount, onMounted, provide, ref, useAttrs, watch } from 'vue';

const props = withDefaults(
    defineProps<{
        as?: string | object;
        cacheFor?: number;
        data?: RequestPayload;
        headers?: Record<string, string>;
        href: string;
        method?: Method;
        navigate?: boolean | null;
        prefetch?: boolean | string | string[];
        queryStringArrayFormat?: string;
    }>(),
    {
        as: 'a',
        cacheFor: 30000,
        data: () => ({}),
        headers: () => ({}),
        size: null,
        method: 'get',
        navigate: null,
        position: null,
        prefetch: false,
        queryStringArrayFormat: 'brackets',
    },
);

const loading = ref(false);
const modalStack = useModalStack();
const modalContext = ref<any>(null);

provide(modalContextKey, modalContext);

const emit = defineEmits([
    'after-leave',
    'blur',
    'close',
    'error',
    'focus',
    'start',
    'success',
    'prefetching',
    'prefetched',
]);

const isBlurred = ref(false);

const hoverTimeout = ref<ReturnType<typeof setTimeout> | null>(null);

const prefetchModes = computed(() => {
    if (props.prefetch === true) {
        return ['hover'];
    }
    if (props.prefetch === false) {
        return [];
    }
    if (Array.isArray(props.prefetch)) {
        return props.prefetch;
    }

    return [props.prefetch];
});

const doPrefetch = () => {
    prefetchModal(props.href, {
        method: props.method,
        data: props.data,
        headers: props.headers,
        queryStringArrayFormat: props.queryStringArrayFormat as any,
        cacheFor: props.cacheFor,
        onPrefetching: () => emit('prefetching'),
        onPrefetched: () => emit('prefetched'),
    });
};

const onMouseenter = () => {
    if (!prefetchModes.value.includes('hover')) return;

    hoverTimeout.value = setTimeout(() => {
        doPrefetch();
    }, 75);
};

const onMouseleave = () => {
    if (hoverTimeout.value) {
        clearTimeout(hoverTimeout.value);
        hoverTimeout.value = null;
    }
};

const onMousedown = (event: MouseEvent) => {
    if (!prefetchModes.value.includes('click')) return;
    if (event.button !== 0) return;

    doPrefetch();
};

onMounted(() => {
    if (prefetchModes.value.includes('mount')) {
        doPrefetch();
    }
});

watch(
    () => modalContext.value?.onTopOfStack,
    (onTopOfStack: boolean) => {
        if (modalContext.value) {
            if (onTopOfStack && isBlurred.value) {
                emit('focus');
            } else if (!onTopOfStack) {
                emit('blur');
            }

            isBlurred.value = !onTopOfStack;
        }
    },
);

const unsubscribeEventListeners = ref<(() => void) | null>(null);

onBeforeUnmount(() => {
    unsubscribeEventListeners.value?.();
    if (hoverTimeout.value) {
        clearTimeout(hoverTimeout.value);
    }
});

const $attrs = useAttrs();

const registerEventListeners = () => {
    unsubscribeEventListeners.value = modalContext.value.registerEventListenersFromAttrs($attrs);
};

watch(modalContext, (value: any, oldValue: any) => {
    if (value && !oldValue) {
        registerEventListeners();
        emit('success');
    }
});

const onClose = () => {
    emit('close');
};

const onAfterLeave = () => {
    modalContext.value = null;
    emit('after-leave');
};

const handle = () => {
    if (loading.value) {
        return;
    }

    if (!props.href.startsWith('#')) {
        loading.value = true;
        emit('start');
    }

    modalStack
        .visit(
            props.href,
            props.method,
            props.data,
            props.headers,
            rejectNullValues(onlyKeys(props, modalPropNames) as any) as any,
            onClose,
            onAfterLeave,
            props.queryStringArrayFormat as any,
            props.navigate ?? false,
        )
        .then((context: any) => {
            modalContext.value = context;
        })
        .catch((error: unknown) => {
            console.error(error);
            emit('error', error);
        })
        .finally(() => (loading.value = false));
};
</script>

<template>
    <component
        v-bind="$attrs"
        :is="as"
        :href="href"
        @click.prevent="handle"
        @mouseenter="onMouseenter"
        @mouseleave="onMouseleave"
        @mousedown="onMousedown"
    >
        <slot :loading="loading" />
    </component>
</template>
