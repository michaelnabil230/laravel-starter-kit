<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
import { cn } from '@/dashboard/lib/utils';
import { useForwardProps } from 'reka-ui';
import { onMounted, ref, watchEffect } from 'vue';
import { type TextareaProps } from './types';

defineOptions({ inheritAttrs: false });

const props = withDefaults(defineProps<TextareaProps>(), {
    hasError: false,
    autoSize: false,
});

const forwardedProps = useForwardProps(props);

const model = defineModel<string | null>();

if (model.value === undefined) {
    model.value = props.defaultValue ?? null;
}

const textarea = ref<HTMLTextAreaElement>();

defineExpose({ textarea });

const useAutoSizeTextarea = (element: HTMLTextAreaElement | null) => {
    const resizeTextarea = () => {
        if (!element) return;
        element.style.height = 'auto';
        element.style.height = element.scrollHeight + 'px';
    };

    watchEffect((onInvalidate) => {
        if (!element) return;
        resizeTextarea();
        element.addEventListener('input', resizeTextarea);
        onInvalidate(() => element.removeEventListener('input', resizeTextarea));
    });
};

onMounted(() => {
    if (props.autoSize && textarea.value) {
        useAutoSizeTextarea(textarea.value!);
    }
});
</script>

<template>
    <div class="relative">
        <textarea
            ref="textarea"
            v-model="model"
            v-bind="forwardedProps"
            :class="
                cn(
                    'block w-full resize-none rounded-lg border-gray-200 px-3 py-2 text-sm placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-transparent dark:text-neutral-300 dark:placeholder:text-white/60 dark:focus:ring-neutral-600',
                    {
                        'border-red-500 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:focus:border-red-500 dark:focus:ring-red-500':
                            hasError,
                        'overflow-y-hidden': autoSize,
                    },
                    props.class,
                )
            "
        ></textarea>

        <div class="absolute inset-e-3 top-2 z-20 flex cursor-pointer items-center gap-2">
            <slot name="icon" />
            <SvgIcon v-if="hasError" name="icons/warning" class="size-4 shrink-0 text-red-500" />
        </div>
    </div>
</template>
