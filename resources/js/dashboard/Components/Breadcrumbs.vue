<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
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
            <SvgIcon
                v-if="index !== breadcrumbs.length - 1"
                name="icons/chevron-right"
                class="mx-1 size-4 shrink-0 overflow-visible text-gray-400 dark:text-neutral-600"
            />
        </li>
    </ol>
</template>
