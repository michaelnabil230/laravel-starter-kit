<script setup lang="ts">
import { cn } from '@/dashboard/lib/utils';
import { HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();

const model = defineModel<any | null>();
</script>

<template>
    <input
        type="checkbox"
        v-model="model"
        :class="
            cn(
                'shrink-0 rounded-sm border-gray-300 text-blue-700 focus:ring-blue-500 indeterminate:border-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800',
                props.class,
            )
        "
    />
</template>
