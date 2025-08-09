<script setup lang="ts">
import { CollapsibleContent, CollapsibleRoot, CollapsibleTrigger } from 'reka-ui';

defineProps<{
    label: string;
    defaultOpen?: boolean;
}>();
</script>

<template>
    <CollapsibleRoot class="mb-1.5 px-5" as="li" :defaultOpen="defaultOpen">
        <CollapsibleTrigger
            class="group flex w-full cursor-pointer gap-x-3 rounded-lg px-3 py-2 text-start text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 data-[state=open]:bg-gray-100 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:data-[state=open]:bg-neutral-700"
        >
            <slot name="icon" />

            {{ label }}

            <svg
                class="ms-auto mt-1 size-3.5 shrink-0 transition group-data-[state=open]:rotate-180"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path d="m6 9 6 6 6-6" />
            </svg>
        </CollapsibleTrigger>

        <CollapsibleContent
            class="data-[state=closed]:animate-collapsible-up data-[state=open]:animate-collapsible-down w-full overflow-hidden"
        >
            <ul
                class="relative mt-1 flex flex-col gap-y-1 ps-7 before:absolute before:start-4.5 before:top-0 before:h-full before:w-0.5 before:bg-gray-100 dark:before:bg-neutral-700"
            >
                <slot name="list" />
            </ul>
        </CollapsibleContent>
    </CollapsibleRoot>
</template>
