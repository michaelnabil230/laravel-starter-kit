<script setup lang="ts">
import { cn } from '@/dashboard/lib/utils';
import { HTMLAttributes } from 'vue';
import { alertVariants, AlertVariants } from './Index';

const props = defineProps<{
    variant?: AlertVariants['variant'];
    color?: AlertVariants['color'];
    class?: HTMLAttributes['class'];
    value?: string;
}>();
</script>

<template>
    <div :class="cn(alertVariants({ variant, color }), props.class)">
        <template v-if="value">{{ value }}</template>
        <template v-else><slot /></template>
    </div>
</template>
