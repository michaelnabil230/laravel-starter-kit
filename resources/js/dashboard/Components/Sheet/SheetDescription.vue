<script setup lang="ts">
import { cn } from '@/dashboard/lib/utils';
import { DialogDescription } from 'reka-ui';
import { type HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <DialogDescription as="p" :class="cn('text-gray-500 dark:text-neutral-500', props.class)">
        <slot />
    </DialogDescription>
</template>
