<script setup lang="ts">
import { cn } from '@/dashboard/lib/utils';
import { Link } from '@inertiajs/vue3';
import { HTMLAttributes } from 'vue';
import { buttonVariants, ButtonVariants } from '.';

const props = defineProps<{
    href: string;
    variant?: ButtonVariants['variant'];
    color?: ButtonVariants['color'];
    class?: HTMLAttributes['class'];
    value?: string;
}>();
</script>

<template>
    <Link :href="href" :class="cn(buttonVariants({ variant, color }), props.class)">
        <template v-if="value">{{ value }}</template>
        <template v-else><slot /></template>
    </Link>
</template>
