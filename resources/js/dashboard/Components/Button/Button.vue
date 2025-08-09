<script setup lang="ts">
import { cn } from '@/dashboard/lib/utils';
import { useForwardProps } from 'reka-ui';
import { computed } from 'vue';
import { buttonVariants } from './Index';
import { type ButtonProps } from './types';

const props = withDefaults(defineProps<ButtonProps>(), {
    variant: 'contained',
    color: 'primary',
    size: 'small',
    type: 'button',
    disabled: false,
});

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, variant, color, size, ...delegated } = props;

    return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
    <button v-bind="forwardedProps" :class="cn(buttonVariants({ variant, color, size }), props.class)">
        <template v-if="value">{{ value }}</template>
        <template v-else><slot /></template>
    </button>
</template>
