<script setup lang="ts">
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import TextInput from '@/dashboard/Components/Inputs/TextInput.vue';
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';

defineOptions({ inheritAttrs: false });

defineProps<{
    resource: string;
}>();

const model = defineModel<string>();
</script>

<template>
    <div class="relative">
        <div class="pointer-events-none absolute inset-y-0 inset-s-0 z-20 flex items-center ps-3.5">
            <SvgIcon name="icons/search" class="size-4 shrink-0 text-gray-500 dark:text-neutral-400" />
        </div>
        <InputLabel
            class="sr-only"
            :value="__('global.search.resource', { resource: __('resources.' + resource + '.plural') })"
            :for="'search_' + resource"
        />
        <TextInput
            type="text"
            :id="'search_' + resource"
            class="bg-gray-100 py-1.75 ps-10 pe-8 focus:bg-white dark:bg-neutral-700 dark:focus:bg-neutral-800"
            :placeholder="__('global.search.resource', { resource: __('resources.' + resource + '.plural') })"
            v-model="model"
        />
    </div>
</template>
