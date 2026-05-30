<script setup lang="ts">
import { buttonVariants } from '@/dashboard/Components/Button/Index';
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
import { cn } from '@/dashboard/lib/utils';
import { DropdownMenuContent, DropdownMenuPortal, DropdownMenuRoot, DropdownMenuTrigger } from 'reka-ui';

defineProps<{
    resource: string;
    onImport: VoidFunction;
    onExport: VoidFunction;
}>();
</script>

<template>
    <DropdownMenuRoot>
        <DropdownMenuTrigger :class="cn(buttonVariants({ variant: 'outlined', color: 'secondary' }), 'text-xs')">
            <SvgIcon name="icons/import-export" />
            {{ __('import.label') }} / {{ __('export.label') }}
            <SvgIcon name="icons/chevron-down" class="size-3.5 shrink-0" />
        </DropdownMenuTrigger>

        <DropdownMenuPortal>
            <DropdownMenuContent
                class="duration w-40 rounded-xl bg-white p-1 opacity-0 shadow-xl transition-[opacity,margin] data-[state=open]:opacity-100 dark:bg-neutral-900"
            >
                <button
                    @click="onImport"
                    type="button"
                    class="flex w-full cursor-pointer gap-x-3 rounded-lg px-2 py-1.5 text-[13px] text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                >
                    <SvgIcon name="icons/import" class="mt-0.5 size-3.5 shrink-0" />
                    {{ __('import.resource', { resource: __('resources.' + resource + '.plural') }) }}
                </button>

                <button
                    @click="onExport"
                    type="button"
                    class="flex w-full cursor-pointer gap-x-3 rounded-lg px-2 py-1.5 text-[13px] text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                >
                    <SvgIcon name="icons/export" class="mt-0.5 size-3.5 shrink-0" />
                    {{ __('export.resource', { resource: __('resources.' + resource + '.plural') }) }}
                </button>
            </DropdownMenuContent>
        </DropdownMenuPortal>
    </DropdownMenuRoot>
</template>
