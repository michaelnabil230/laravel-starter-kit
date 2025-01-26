<script setup lang="ts">
import { SelectItem, SelectItemIndicator, type SelectItemProps, SelectItemText } from 'reka-ui';

const props = defineProps<SelectItemProps>();
</script>

<template>
    <SelectItem
        v-bind="props"
        class="flex w-full cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden data-[state=checked]:font-semibold dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
    >
        <SelectItemText>
            <slot />
        </SelectItemText>
        <SelectItemIndicator>
            <svg
                class="size-3.5 shrink-0 text-blue-700 dark:text-blue-500"
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
                <polyline points="20 6 9 17 4 12" />
            </svg>
        </SelectItemIndicator>
    </SelectItem>
</template>
