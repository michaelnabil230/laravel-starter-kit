<script setup lang="ts">
import Tooltip from '@/dashboard/Components/Tooltip.vue';
import { cn } from '@/dashboard/lib/utils';
import { HTMLAttributes } from 'vue';

const props = defineProps<{
    value: string;
    for: string;
    class?: HTMLAttributes['class'];
    labelClass?: HTMLAttributes['class'];
    contentTooltip?: string;
}>();
</script>

<template>
    <div :class="cn('mb-2 flex items-center gap-x-2', props.class)">
        <label :for="props.for" :class="cn('block text-sm text-gray-500 dark:text-neutral-500', props.labelClass)">
            {{ value }}
        </label>

        <Tooltip v-if="contentTooltip" :content="contentTooltip">
            <template #trigger>
                <svg
                    class="size-4 text-gray-500 dark:text-neutral-500"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16V11.5" />
                    <path d="M12 8.01172V8.00172" />
                </svg>
            </template>
        </Tooltip>
    </div>
</template>
