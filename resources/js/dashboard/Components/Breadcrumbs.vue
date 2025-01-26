<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    breadcrumbs: Breadcrumbs;
}>();
</script>

<template>
    <ol class="flex items-center whitespace-nowrap">
        <li
            v-for="(breadcrumb, index) in breadcrumbs"
            :key="index"
            class="flex items-center truncate"
            aria-current="page"
        >
            <Component
                :is="!breadcrumb.href ? 'span' : Link"
                :href="breadcrumb.href ?? '#'"
                class="text-sm"
                :class="
                    index === breadcrumbs.length - 1
                        ? 'font-semibold text-gray-800 dark:text-neutral-200'
                        : 'text-gray-600 dark:text-neutral-500'
                "
            >
                {{ breadcrumb.text }}
            </Component>
            <svg
                v-if="index !== breadcrumbs.length - 1"
                class="mx-1 size-4 flex-shrink-0 overflow-visible text-gray-400 dark:text-neutral-600"
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
                <path d="m9 18 6-6-6-6" />
            </svg>
        </li>
    </ol>
</template>
