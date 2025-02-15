<script setup lang="ts">
import { filter } from 'lodash';
import { HSOverlay, ICollectionItem } from 'preline/preline';
import { computed, nextTick, onBeforeUnmount, onMounted, onUnmounted, Ref, ref, useAttrs } from 'vue';

defineOptions({
    inheritAttrs: false,
});

const props = withDefaults(
    defineProps<{
        name: string;
        modalStyle?: 'window' | 'fullscreen';
        size?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '3xl' | '4xl' | '5xl' | '6xl' | '7xl';
    }>(),
    {
        size: 'md',
        modalStyle: 'window',
    },
);

const emit = defineEmits(['opened', 'closed', 'close-via-escape']);

const attrs: any = useAttrs();

const sizesClasses = computed(() => {
    return {
        sm: 'max-w-sm sm:w-full sm:mx-auto',
        md: 'max-w-md md:w-full md:mx-auto',
        lg: 'max-w-lg lg:w-full lg:mx-auto',
        xl: 'max-w-xl xl:w-full xl:mx-auto',
        '2xl': 'max-w-2xl 2xl:w-full 2xl:mx-auto',
        '3xl': 'max-w-3xl w-full mx-auto',
        '4xl': 'max-w-4xl w-full mx-auto',
        '5xl': 'max-w-5xl w-full mx-auto',
        '6xl': 'max-w-6xl w-full mx-auto',
        '7xl': 'max-w-7xl w-full mx-auto',
    };
});

const isWindow = computed(() => props.modalStyle === 'window');

const contentClasses = computed(() => {
    let windowedClasses: Record<string, string> = isWindow.value ? sizesClasses.value : {};

    return filter([windowedClasses[props.size], isWindow.value ? 'hs-overlay-open:mt-7' : 'h-full']);
});

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
        class="hs-overlay pointer-events-none fixed start-0 top-0 z-[80] hidden size-full overflow-y-auto overflow-x-hidden"
        role="dialog"
        tabindex="-1"
        :aria-labelledby="name + '-label'"
    >
        <div
            class="m-3 mt-0 flex min-h-[calc(100%-3.5rem)] items-center opacity-0 transition-all ease-out hs-overlay-open:opacity-100 hs-overlay-open:duration-500"
            :class="contentClasses"
        >
            <div
                :class="{
                    'h-full max-h-full': !isWindow,
                }"
                v-bind="attrs"
                class="flex flex-col w-full bg-white border shadow-sm pointer-events-auto rounded-xl dark:border-neutral-800 dark:bg-neutral-800"
            >
                <slot />
            </div>
        </div>
    </div>
</template>
