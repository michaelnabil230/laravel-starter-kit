<script setup lang="ts">
import { DropdownMenuTrigger, type DropdownMenuTriggerProps, useForwardProps } from 'reka-ui';

const props = defineProps<DropdownMenuTriggerProps>();

const forwardedProps = useForwardProps(props);
</script>

<template>
    <DropdownMenuTrigger
        v-bind="forwardedProps"
        class="-ms-px inline-flex cursor-pointer items-center justify-center gap-x-2 border border-stone-200 p-2 text-stone-800 first:ms-0 first:rounded-s-lg last:rounded-e-lg focus:bg-stone-50 focus:outline-hidden dark:border-neutral-700 dark:text-neutral-300 dark:focus:bg-neutral-700"
    >
        <svg
            class="size-3.5 shrink-0"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <circle cx="12" cy="12" r="1" />
            <circle cx="12" cy="5" r="1" />
            <circle cx="12" cy="19" r="1" />
        </svg>
    </DropdownMenuTrigger>
</template>
