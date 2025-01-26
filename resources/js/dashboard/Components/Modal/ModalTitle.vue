<script setup lang="ts">
import { cn } from '@/dashboard/lib/utils';
import { DialogTitle } from 'reka-ui';
import { type HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <DialogTitle as="h3" :class="cn('font-medium text-gray-800 dark:text-neutral-200', props.class)">
        <slot />
    </DialogTitle>
</template>
