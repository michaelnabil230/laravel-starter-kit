<script setup lang="ts">
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import TextInput from '@/dashboard/Components/Inputs/TextInput.vue';

defineProps<{
    resource: string;
}>();
</script>

<template>
    <div class="relative">
        <div class="pointer-events-none absolute inset-y-0 start-0 z-20 flex items-center ps-3.5">
            <svg
                class="size-4 shrink-0 text-gray-500 dark:text-neutral-400"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
        </div>
        <InputLabel
            class="sr-only"
            :value="__('global.search.resource', { resource: __('resources.' + resource + '.plural') })"
            :for="'search_' + resource"
        />
        <TextInput
            type="text"
            :id="'search_' + resource"
            class="bg-gray-100 py-[7px] ps-10 pe-8 focus:bg-white dark:bg-neutral-700 dark:focus:bg-neutral-800"
            :placeholder="__('global.search.resource', { resource: __('resources.' + resource + '.plural') })"
        />
    </div>
</template>
