<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
defineProps<{
    href: string;
    value?: string;
}>();
</script>

<template>
    <Link
        :href="href"
        class="inline-flex items-center gap-x-2 rounded-lg border border-transparent bg-red-500 px-3 py-2 text-sm font-medium text-white hover:bg-red-600 focus:bg-red-600 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50"
    >
        <template v-if="value">{{ value }}</template>
        <template v-else><slot /></template>
    </Link>
</template>
