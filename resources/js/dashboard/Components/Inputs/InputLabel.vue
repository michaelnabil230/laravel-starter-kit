<script setup lang="ts">
import Tooltip from '@/dashboard/Components/Tooltip.vue';

defineProps<{
    value?: string;
    contentTooltip?: string;
}>();
</script>

<template>
    <div class="flex items-center gap-x-2">
        <label class="block text-sm text-gray-500 dark:text-neutral-500">
            <template v-if="value">{{ value }}</template>
            <template v-else><slot /></template>
        </label>

        <Tooltip v-if="contentTooltip" :content="contentTooltip">
            <template #trigger>
                <svg
                    class="size-4 text-gray-500 dark:text-neutral-500"
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
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16V11.5" />
                    <path d="M12 8.01172V8.00172" />
                </svg>
            </template>
        </Tooltip>
    </div>
</template>
