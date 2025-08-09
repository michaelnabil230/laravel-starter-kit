<script setup lang="ts">
defineProps<{
    value?: string;
}>();
</script>

<template>
    <p class="mt-2 inline-flex items-center gap-2 text-sm text-gray-500 dark:text-neutral-500">
        <svg
            class="size-4"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <circle cx="12" cy="12" r="10" />
            <path d="M12 16V11.5" />
            <path d="M12 8.01172V8.00172" />
        </svg>
        <template v-if="value">{{ value }}</template>
        <template v-else><slot /></template>
    </p>
</template>
