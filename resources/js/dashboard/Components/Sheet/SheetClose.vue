<script setup lang="ts">
import { cn } from '@/dashboard/lib/utils';
import { DialogClose } from 'reka-ui';
import { type HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <DialogClose
        :class="
            cn(
                'inline-flex size-6 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:bg-gray-200 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:bg-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-600 dark:focus:bg-neutral-600',
                props.class,
            )
        "
    >
        <span class="sr-only">{{ __('global.closure.close') }}</span>
        <svg
            class="size-4 shrink-0"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
        </svg>
    </DialogClose>
</template>
