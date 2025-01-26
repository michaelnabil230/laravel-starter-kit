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
        type="radio"
        v-model="model"
        :class="
            cn(
                'shrink-0 rounded-full border-gray-300 text-blue-700 checked:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:checked:border-blue-500 dark:checked:bg-blue-500 dark:focus:ring-offset-gray-800',
                props.class,
            )
        "
    />
</template>
