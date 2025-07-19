<script setup lang="ts">
import { TooltipContent, TooltipPortal, TooltipProvider, TooltipRoot, TooltipTrigger } from 'reka-ui';

withDefaults(
    defineProps<{
        align?: 'start' | 'center' | 'end';
        sideOffset?: number;
        content?: string;
    }>(),
    {
        align: 'center',
        sideOffset: 5,
    },
);
</script>

<template>
    <TooltipProvider>
        <TooltipRoot>
            <TooltipTrigger as="div" as-child>
                <slot name="trigger" />
            </TooltipTrigger>
            <TooltipPortal>
                <TooltipContent
                    :sideOffset="sideOffset"
                    :align="align"
                    class="animate-in fade-in-0 zoom-in-95 data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=closed]:zoom-out-95 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 z-100 max-w-96 overflow-hidden rounded-md bg-gray-900 px-2 py-1 text-xs font-medium text-white shadow-xl dark:bg-neutral-700"
                >
                    <template v-if="content">{{ content }}</template>
                    <template v-else><slot name="content" /></template>
                </TooltipContent>
            </TooltipPortal>
        </TooltipRoot>
    </TooltipProvider>
</template>
