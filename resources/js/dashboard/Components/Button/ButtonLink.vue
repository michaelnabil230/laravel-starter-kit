<script setup lang="ts">
import { cn } from '@/dashboard/lib/utils';
import { InertiaLinkProps, Link } from '@inertiajs/vue3';
import { useForwardProps } from 'reka-ui';
import { computed } from 'vue';
import { buttonVariants } from './Index';
import { type BaseButtonProps } from './types';

const props = withDefaults(defineProps<InertiaLinkProps & BaseButtonProps>(), {
    variant: 'contained',
    color: 'primary',
    size: 'small',
});

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, variant, color, size, ...delegated } = props;

    return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
    <Link :class="cn(buttonVariants({ variant, color, size }), props.class)" v-bind="forwardedProps">
        <template v-if="value">{{ value }}</template>
        <template v-else><slot /></template>
    </Link>
</template>
