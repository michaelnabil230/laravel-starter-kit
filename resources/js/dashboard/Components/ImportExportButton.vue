<script setup lang="ts">
import { buttonVariants } from '@/dashboard/Components/Button/Index';
import { cn } from '@/dashboard/lib/utils';
import { DropdownMenuContent, DropdownMenuPortal, DropdownMenuRoot, DropdownMenuTrigger } from 'reka-ui';

defineProps<{
    resource: string;
    onImport: () => void;
    onExport: () => void;
}>();
</script>

<template>
    <DropdownMenuRoot>
        <!-- Button -->
        <DropdownMenuTrigger :class="cn(buttonVariants({ variant: 'outlined', color: 'secondary' }), 'text-xs')">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path d="m3 16 4 4 4-4" />
                <path d="M7 20V4" />
                <path d="m21 8-4-4-4 4" />
                <path d="M17 4v16" />
            </svg>
            {{ __('import.label') }} / {{ __('export.label') }}
            <svg
                class="size-3.5 shrink-0"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path d="m6 9 6 6 6-6" />
            </svg>
        </DropdownMenuTrigger>
        <!-- End Button -->

        <DropdownMenuPortal>
            <DropdownMenuContent
                class="duration w-40 rounded-xl bg-white p-1 opacity-0 shadow-xl transition-[opacity,margin] data-[state=open]:opacity-100 dark:bg-neutral-900"
            >
                <button
                    @click="onImport"
                    type="button"
                    class="flex w-full cursor-pointer gap-x-3 rounded-lg px-2 py-1.5 text-[13px] text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                >
                    <svg
                        class="mt-0.5 size-3.5 shrink-0"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" x2="12" y1="15" y2="3" />
                    </svg>
                    {{ __('import.resource', { resource }) }}
                </button>

                <button
                    @click="onExport"
                    type="button"
                    class="flex w-full cursor-pointer gap-x-3 rounded-lg px-2 py-1.5 text-[13px] text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                >
                    <svg
                        class="mt-0.5 size-3.5 shrink-0"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" x2="12" y1="3" y2="15" />
                    </svg>
                    {{ __('export.resource', { resource }) }}
                </button>
            </DropdownMenuContent>
        </DropdownMenuPortal>
        <!-- Dropdown -->
    </DropdownMenuRoot>
</template>
