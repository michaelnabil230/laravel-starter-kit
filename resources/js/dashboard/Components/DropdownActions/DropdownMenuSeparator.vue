<script setup lang="ts">
import { cn } from '@/dashboard/lib/utils';
import { DropdownMenuSeparator, type DropdownMenuSeparatorProps } from 'reka-ui';
import { computed, type HTMLAttributes } from 'vue';

const props = defineProps<
    DropdownMenuSeparatorProps & {
        class?: HTMLAttributes['class'];
    }
>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});
</script>

<template>
    <DropdownMenuSeparator
        v-bind="delegatedProps"
        :class="cn('-mx-1 my-1 border-t border-stone-200 dark:border-neutral-800', props.class)"
    />
</template>
