<script setup lang="ts">
import { cn } from '@/dashboard/lib/utils';
import { HTMLAttributes } from 'vue';
import { buttonVariants, ButtonVariants } from './Index';

const props = defineProps<{
    variant?: ButtonVariants['variant'];
    color?: ButtonVariants['color'];
    class?: HTMLAttributes['class'];
    value?: string;
}>();
</script>

<template>
    <button :class="cn(buttonVariants({ variant, color }), props.class)">
        <template v-if="value">{{ value }}</template>
        <template v-else><slot /></template>
    </button>
</template>
