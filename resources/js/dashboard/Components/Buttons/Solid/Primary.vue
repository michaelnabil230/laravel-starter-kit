<script setup lang="ts">
defineProps<{
    value?: string;
}>();
</script>

<template>
    <button
        class="inline-flex items-center gap-x-2 rounded-lg border border-transparent bg-blue-500 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:bg-blue-700 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50"
    >
        <template v-if="value">{{ value }}</template>
        <template v-else><slot /></template>
    </button>
</template>
