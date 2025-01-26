<script setup lang="ts">
import useShortcutController from '@/dashboard/composables/useShortcutController';
import { DialogContent, DialogOverlay, DialogPortal, DialogRoot } from 'reka-ui';
import { onUnmounted, watch } from 'vue';
import { ModalVariants, modalVariants } from './Index';

defineProps<{
    size?: ModalVariants['size'];
}>();

const shortcutController = useShortcutController();

const emit = defineEmits(['opened', 'closed', 'close-via-escape']);

const modal = defineModel<boolean>({ required: true });

watch(modal, (value) => {
    if (value) {
        opened();
    } else {
        closed();
    }
});

onUnmounted(() => removeEscapeListenerAndResume);

const closeOnEscape = (event: KeyboardEvent) => {
    if (event.key === 'Escape') {
        emit('close-via-escape', event);
    }
};

const opened = () => {
    emit('opened');
    document.addEventListener('keydown', closeOnEscape);
    shortcutController.pauseShortcuts();
};

const closed = () => {
    emit('closed');
    removeEscapeListenerAndResume();
};

const removeEscapeListenerAndResume = () => {
    document.removeEventListener('keydown', closeOnEscape);
    shortcutController.resumeShortcuts();
};
</script>

<template>
    <DialogRoot v-model:open="modal">
        <DialogPortal>
            <DialogOverlay
                class="data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 fixed inset-0 z-60 bg-gray-900/50 dark:bg-neutral-900/80"
            />
            <DialogContent :class="modalVariants({ size })">
                <div
                    class="pointer-events-auto flex w-full flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-2xs dark:border-neutral-800 dark:bg-neutral-800"
                >
                    <slot />
                </div>
            </DialogContent>
        </DialogPortal>
    </DialogRoot>
</template>
