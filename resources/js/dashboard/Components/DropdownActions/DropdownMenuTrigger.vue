<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
import { DropdownMenuTrigger, type DropdownMenuTriggerProps, useForwardProps } from 'reka-ui';

const props = defineProps<DropdownMenuTriggerProps>();

const forwardedProps = useForwardProps(props);
</script>

<template>
    <DropdownMenuTrigger
        v-bind="forwardedProps"
        class="-ms-px inline-flex cursor-pointer items-center justify-center gap-x-2 border border-stone-200 p-2 text-stone-800 first:ms-0 first:rounded-s-lg last:rounded-e-lg focus:bg-stone-50 focus:outline-hidden dark:border-neutral-700 dark:text-neutral-300 dark:focus:bg-neutral-700"
    >
        <SvgIcon name="icons/dots-vertical" class="size-3.5 shrink-0" />
    </DropdownMenuTrigger>
</template>
