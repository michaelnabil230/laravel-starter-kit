<script setup lang="ts">
defineProps<{
    value?: string;
}>();
</script>

<template>
    <label class="block text-sm text-gray-500 dark:text-neutral-500">
        <template v-if="value">{{ value }}</template>
        <template v-else><slot /></template>
    </label>
</template>
