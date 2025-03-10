<script setup lang="ts">
import { SelectItem, SelectItemIndicator, type SelectItemProps, SelectItemText } from 'reka-ui';

const props = defineProps<SelectItemProps>();
</script>

<template>
    <SelectItem
        v-bind="props"
        class="cursor-pointer data-[state=checked]:font-semibold py-2 px-4 w-full flex justify-between items-center text-sm text-gray-800 hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800"
    >
        <SelectItemText>
            <slot />
        </SelectItemText>
        <SelectItemIndicator>
            <svg
                class="shrink-0 size-3.5 text-blue-700 dark:text-blue-500"
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
