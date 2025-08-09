<script setup lang="ts">
import useLocalization from '@/dashboard/composables/useLocalization';
import { cn } from '@/dashboard/lib/utils';
import { computed, HTMLAttributes } from 'vue';

const { __ } = useLocalization();

const props = withDefaults(
    defineProps<{
        text?: string;
        size?: 'sm' | 'md' | 'lg';
        loadingClass?: HTMLAttributes['class'];
        textClass?: HTMLAttributes['class'];
    }>(),
    {
        size: 'md',
    },
);

const sizeClass = computed(() => {
    return {
        sm: 'size-3 border-1',
        md: 'size-4 border-2',
        lg: 'size-6 border-4',
    }[props.size];
});

const textSizeClass = computed(() => {
    return {
        sm: 'text-xs',
        md: 'text-sm',
        lg: 'text-base',
    }[props.size];
});

const loadingText = computed(() => {
    return props.text ?? `${__('global.loading')}...`;
});
</script>

<template>
    <div class="flex flex-row items-center justify-center gap-1.5">
        <div
            :class="
                cn(
                    'inline-block animate-spin rounded-full border-current border-t-transparent text-blue-700 dark:text-blue-500',
                    sizeClass,
                    props.loadingClass,
                )
            "
        />
        <span :class="cn('text-gray-800 dark:text-neutral-200', textSizeClass, props.textClass)">
            {{ loadingText }}
        </span>
    </div>
</template>
