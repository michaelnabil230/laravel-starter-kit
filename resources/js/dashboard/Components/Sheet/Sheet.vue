<script setup lang="ts">
import useShortcutController from '@/dashboard/composables/useShortcutController';
import { DialogContent, DialogOverlay, DialogPortal, DialogRoot } from 'reka-ui';
import { onUnmounted, watch } from 'vue';
import { sheetVariants, SheetVariants } from './Index';

defineProps<{
    size?: SheetVariants['size'];
    side?: SheetVariants['side'];
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
            <DialogContent :class="sheetVariants({ size, side })">
                <div
                    class="flex size-full flex-1 flex-col border-s border-gray-200 bg-white dark:border-neutral-700 dark:bg-neutral-800"
                >
                    <slot />
                </div>
            </DialogContent>
        </DialogPortal>
    </DialogRoot>
</template>
