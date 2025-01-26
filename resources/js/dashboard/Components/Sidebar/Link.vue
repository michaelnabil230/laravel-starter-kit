<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    href: string;
}>();
</script>

<template>
    <Link
        :href="route(href)"
        :class="{
            'bg-gray-100 dark:bg-neutral-700': route().current(href),
        }"
        class="flex gap-x-3 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
    >
        <slot />
    </Link>
</template>
