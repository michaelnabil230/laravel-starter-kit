<script setup lang="ts">
defineProps<{
    value?: string;
}>();
</script>

<template>
    <button
        class="inline-flex items-center gap-x-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 focus:bg-gray-50 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
    >
        <template v-if="value">{{ value }}</template>
        <template v-else><slot /></template>
    </button>
</template>
