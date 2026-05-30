<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';

defineProps<{
    boolean: boolean;
}>();
</script>

<template>
    <div>
        <span
            v-if="boolean"
            class="inline-flex items-center gap-x-1 rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-500/10 dark:text-blue-500"
        >
            <SvgIcon name="icons/check-circle" class="size-3 shrink-0" />
            {{ __('global.confirmation.yes') }}
        </span>

        <span
            v-else
            class="inline-flex items-center gap-x-1 rounded-full bg-red-100 px-1.5 py-1 text-xs font-medium text-red-800 dark:bg-red-500/10 dark:text-red-500"
        >
            <SvgIcon name="icons/alert-triangle" class="size-3 shrink-0" />
            {{ __('global.confirmation.no') }}
        </span>
    </div>
</template>
