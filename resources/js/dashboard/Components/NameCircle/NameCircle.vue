<script setup lang="ts">
import { computed } from 'vue';
import { nameCircleVariants } from './Index';
import { NameCircleProps } from './types';

const props = withDefaults(defineProps<NameCircleProps>(), {
    size: 'medium',
});

const initials = computed(() => {
    if (!props.name) return '';
    return props.name
        .trim()
        .split(/\s+/)
        .map((word) => word[0]?.toUpperCase())
        .join('')
        .slice(0, 2);
});
</script>

<template>
    <div :class="nameCircleVariants({ size })">
        {{ initials }}
    </div>
</template>
