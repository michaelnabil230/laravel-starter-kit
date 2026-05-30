<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';

defineProps<{
    value?: string;
}>();
</script>

<template>
    <p class="mt-2 inline-flex items-center gap-2 text-sm text-gray-500 dark:text-neutral-500">
        <SvgIcon name="icons/info" class="size-4" />
        <template v-if="value">{{ value }}</template>
        <template v-else><slot /></template>
    </p>
</template>
