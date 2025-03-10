<script setup lang="ts">
import { DialogContent, DialogOverlay, DialogPortal, DialogRoot } from 'reka-ui';
import { onUnmounted, watch } from 'vue';
import { sheetVariants, SheetVariants } from '.';

defineProps<{
    size?: SheetVariants['size'];
    side?: SheetVariants['side'];
}>();

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
    dashboardApp.pauseShortcuts();
};

const closed = () => {
    emit('closed');
    removeEscapeListenerAndResume();
};

const removeEscapeListenerAndResume = () => {
    document.removeEventListener('keydown', closeOnEscape);
    dashboardApp.resumeShortcuts();
};
</script>

<template>
    <DialogRoot v-model:open="modal">
        <DialogPortal>
            <DialogOverlay
                class="z-60 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 fixed inset-0 bg-gray-900/50 dark:bg-neutral-900/80"
            />
            <DialogContent :class="sheetVariants({ size, side })">
                <div
                    class="flex size-full flex-1 flex-col border-gray-200 border-s bg-white dark:border-neutral-700 dark:bg-neutral-800"
                >
                    <slot />
                </div>
            </DialogContent>
        </DialogPortal>
    </DialogRoot>
</template>
