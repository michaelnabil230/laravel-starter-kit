<script setup lang="ts">
import {
    useModalStack,
    visitModal,
    type Modal as ModalModel,
    type ReloadOptions,
} from '@/dashboard/composables/modal/modalStack';
import { modalContextKey } from '@/dashboard/composables/useModal';
import useShortcutController from '@/dashboard/composables/useShortcutController';
import type { ModalConfig } from '@/dashboard/types/global';
import { DialogContent, DialogOverlay, DialogPortal, DialogRoot } from 'reka-ui';
import { computed, inject, onBeforeUnmount, onMounted, ref, unref, useAttrs, watch, type Ref } from 'vue';
import { contentVariants, innerVariants } from './Index';
import ModalRenderer from './ModalRenderer.vue';
import type { ModalSlot } from './types';

defineSlots<{
    default(props: ModalSlot): void;
}>();

const props = withDefaults(
    defineProps<
        {
            name?: string;
        } & ModalConfig
    >(),
    {
        name: undefined,
        type: 'modal',
        size: 'md',
        position: 'right',
    },
);

const modalStack = useModalStack();
const modalContext = (
    props.name ? ref<ModalModel | null>(null) : inject(modalContextKey, ref(null))
) as Ref<ModalModel | null>;

if (props.name) {
    modalStack.registerLocalModal(props.name, function (context: ModalModel) {
        modalContext.value = context;
        registerEventListeners();
    });

    onBeforeUnmount(() => {
        modalStack.removeLocalModal(props.name!);
    });
}

onMounted(() => {
    if (!props.name) {
        registerEventListeners();
    }
});

const unsubscribeEventListeners = ref<(() => void) | null>(null);
onBeforeUnmount(() => unsubscribeEventListeners.value?.());

const $attrs = useAttrs();

const registerEventListeners = () => {
    unsubscribeEventListeners.value = modalContext.value?.registerEventListenersFromAttrs($attrs) ?? null;
};

const emits = defineEmits(['after-leave', 'blur', 'open', 'close', 'close-via-escape', 'focus', 'modal-event']);

const modalEmit = (event: string, ...args: unknown[]) => {
    emits('modal-event', event, ...args);
};

defineExpose({
    open: () => {
        if (props.name) {
            visitModal(`#${props.name}`);
        }
    },
    afterLeave: () => modalContext.value?.afterLeave(),
    close: () => modalContext.value?.close(),
    emit: modalEmit,
    getChildModal: () => modalContext.value?.getChildModal(),
    getParentModal: () => modalContext.value?.getParentModal(),
    reload: (...args: unknown[]) => modalContext.value?.reload(...(args as [ReloadOptions?])),
    setOpen: (open: boolean) => {
        if (open) {
            modalContext.value?.setOpen(open);
        } else {
            modalContext.value?.close();
        }
    },

    get config() {
        return modalContext.value?.config;
    },
    get id() {
        return modalContext.value?.id;
    },
    get index() {
        return modalContext.value?.index;
    },
    get isOpen() {
        return modalContext.value?.isOpen;
    },
    get onTopOfStack() {
        return modalContext.value?.onTopOfStack;
    },
    get shouldRender() {
        return modalContext.value?.shouldRender;
    },
});

watch(
    () => unref(modalContext.value?.onTopOfStack),
    (onTopOfStack, previousOnTopOfStack) => {
        if (onTopOfStack && !previousOnTopOfStack) {
            emits('focus');
        } else if (!onTopOfStack && previousOnTopOfStack) {
            emits('blur');
        }
    },
);

const shortcutController = useShortcutController();

const onEscape = (event: KeyboardEvent) => {
    if (event.key === 'Escape') {
        emits('close-via-escape', event);
    }
};

watch(
    () => modalContext.value?.isOpen,
    (isOpen, previousIsOpen) => {
        if (isOpen) {
            emits('open');
            shortcutController.pauseShortcuts();
            document.addEventListener('keydown', onEscape);
        } else if (previousIsOpen === true) {
            emits('close');
            shortcutController.resumeShortcuts();
            document.removeEventListener('keydown', onEscape);
            modalContext.value?.afterLeave();
        }
    },
    { immediate: true },
);

const nextIndex = computed(() => {
    if (!modalContext.value) return undefined;

    const currentIndex = unref(modalContext.value.index);

    const found = modalStack.stack.value.find((m) => m.shouldRender && unref(m.index) > currentIndex);

    return found ? unref(found.index) : undefined;
});

const modalProps = computed(() => {
    const ctx = modalContext.value;
    if (!ctx) return {};

    return unref(ctx.props) ?? {};
});

const config = computed(() => {
    const type = modalContext.value?.config?.type ?? props.type;
    const isSlideover = type === 'slideover';
    const size = modalContext.value?.config?.size ?? props.size;
    const position = modalContext.value?.config?.position ?? props.position;

    return {
        type,
        isSlideover,
        size,
        position,
    };
});

const handleOpenChange = (open: boolean) => {
    if (!open) {
        modalContext.value?.close();
    }
};

defineOptions({
    inheritAttrs: false,
});
</script>

<template>
    <DialogRoot v-if="modalContext?.shouldRender" :open="modalContext.isOpen" @update:open="handleOpenChange">
        <DialogPortal>
            <DialogOverlay
                class="data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 fixed inset-0 z-60 bg-gray-900/50 dark:bg-neutral-900/80"
            />

            <DialogContent
                :class="
                    contentVariants({
                        type: config.type,
                        size: config.size,
                        ...(config.isSlideover ? { position: config.position } : {}),
                    })
                "
            >
                <div :class="innerVariants({ type: config.type })">
                    <slot
                        v-bind="modalProps"
                        :id="modalContext.id"
                        :after-leave="modalContext.afterLeave"
                        :close="modalContext.close"
                        :config="config"
                        :emit="modalEmit"
                        :get-child-modal="modalContext.getChildModal"
                        :get-parent-modal="modalContext.getParentModal"
                        :index="unref(modalContext.index)"
                        :is-open="modalContext.isOpen"
                        :on-top-of-stack="unref(modalContext.onTopOfStack)"
                        :reload="modalContext.reload"
                        :set-open="modalContext.setOpen"
                        :should-render="modalContext.shouldRender"
                    />
                </div>
            </DialogContent>
        </DialogPortal>
    </DialogRoot>

    <ModalRenderer v-if="nextIndex" :index="nextIndex" />
</template>
