<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
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
        <Tooltip v-if="contentTooltip" :content="contentTooltip">
            <template #trigger>
                <SvgIcon name="icons/info" class="size-4 text-gray-500 dark:text-neutral-500" />
            </template>
        </Tooltip>

        <label :for="props.for" :class="cn('block text-sm text-gray-500 dark:text-neutral-500', props.labelClass)">
            {{ value }}
        </label>
    </div>
</template>
