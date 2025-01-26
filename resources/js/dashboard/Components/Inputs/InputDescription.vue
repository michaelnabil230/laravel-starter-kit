<script setup lang="ts">
defineProps<{
    message?: string;
}>();
</script>

<template>
    <p class="mt-2 text-sm text-gray-500 dark:text-neutral-500">
        <template v-if="message">{{ message }}</template>
        <template v-else><slot /></template>
    </p>
</template>
