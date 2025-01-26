<script setup lang="ts">
import { cn } from '@/dashboard/lib/utils';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{ class?: HTMLAttributes['class'] }>();
</script>

<template>
    <div
        :class="
            cn(
                'flex items-center justify-end gap-x-2 border-t border-gray-200 px-4 py-3 dark:border-neutral-700',
                props.class,
            )
        "
    >
        <slot />
    </div>
</template>
