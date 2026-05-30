<script setup lang="ts">
import useLocalization from '@/dashboard/composables/useLocalization';
import { computed } from 'vue';
import ObjectValue from './ObjectValue.vue';

const { __ } = useLocalization();

const props = defineProps<{
    title: string;
    data: Record<string, any>;
}>();

const hasData = computed(() => {
    return props.data && Object.keys(props.data).length > 0;
});
</script>

<template>
    <div
        class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
    >
        <div class="border-b border-gray-200 bg-gray-50 px-5 py-3 dark:border-neutral-700 dark:bg-neutral-800/50">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-neutral-100">
                {{ title }}
            </h3>
        </div>

        <div v-if="!hasData" class="p-8 text-center">
            <p class="text-sm text-gray-500 dark:text-neutral-400">
                {{ __('global.not_found.results') }}
            </p>
        </div>

        <div v-else class="divide-y divide-gray-200 dark:divide-neutral-700">
            <div
                v-for="(value, key) in data"
                :key="key"
                class="px-5 py-4 transition-colors hover:bg-gray-50 dark:hover:bg-neutral-800/50"
            >
                <div class="mb-2">
                    <span class="text-xs font-medium text-gray-700 dark:text-neutral-300">
                        {{ key }}
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <ObjectValue :value="value" />
                </div>
            </div>
        </div>
    </div>
</template>
