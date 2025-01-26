<script setup lang="ts">
import { HSOverlay, ICollectionItem } from 'preline/preline';
import { computed, nextTick, onBeforeUnmount, onMounted, onUnmounted, ref, Ref } from 'vue';

const props = withDefaults(
    defineProps<{
        name: string;
        size?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '3xl' | '4xl' | '5xl' | '6xl' | '7xl';
    }>(),
    {
        size: 'md',
    },
);

const emit = defineEmits(['opened', 'closed', 'close-via-escape']);

const sizesClasses = computed(() => {
    return {
        sm: 'max-w-sm w-full',
        md: 'max-w-md md:w-full',
        lg: 'max-w-lg lg:w-full',
        xl: 'max-w-xl xl:w-full',
        '2xl': 'max-w-2xl 2xl:w-full',
        '3xl': 'max-w-3xl w-full',
        '4xl': 'max-w-4xl w-full',
        '5xl': 'max-w-5xl w-full',
        '6xl': 'max-w-6xl w-full',
        '7xl': 'max-w-7xl w-full',
    };
});

const contentClasses = computed(() => sizesClasses.value[props.size]);

const modal: Ref = ref<Element>();

onMounted(() => {
    nextTick(() => {
        HSOverlay.on('open', modal.value, opened);
        HSOverlay.on('close', modal.value, closed);
    });
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

onBeforeUnmount(() => {
    const hsOverlay = HSOverlay.getInstance(modal.value, true) as ICollectionItem<HSOverlay>;
    hsOverlay.element.close(true);
    hsOverlay.element.destroy();
});
</script>

<template>
    <div
        ref="modal"
        :id="name"
        :aria-labelledby="name + '-label'"
        :class="contentClasses"
        class="hs-overlay fixed end-0 top-0 z-[80] flex hidden size-full flex-1 translate-x-full transform flex-col border-s bg-white transition-all duration-300 hs-overlay-open:translate-x-0 dark:border-neutral-700 dark:bg-neutral-800 rtl:-translate-x-full"
        role="dialog"
    >
        <slot />
    </div>
</template>
