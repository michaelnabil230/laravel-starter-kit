<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
import { Filter } from '@/dashboard/composables/useFilter';
import useLocalization from '@/dashboard/composables/useLocalization';
import { App } from '@/dashboard/types/app';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import InputLabel from './Inputs/InputLabel.vue';
import Select from './Inputs/Select.vue';
import { Option } from './Inputs/types';

const { __ } = useLocalization();

const props = withDefaults(
    defineProps<{
        pagination: App.ApiPaginate<any>;
        filter: Filter;
        perPages?: number[];
    }>(),
    {
        perPages: () => [15, 25, 50, 75, 100],
    },
);

const filteredLinks = computed(() => {
    return props.pagination.meta.links.slice(1, -1);
});

const countLabel = computed(() => {
    const first = props.pagination.meta.per_page * (props.pagination.meta.current_page - 1);

    return `${first + 1}-${first + props.pagination.meta.total} ${__('pagination.of')} ${props.pagination.meta.total}`;
});

const perPages = computed(() => {
    return props.perPages.map((perPage) => {
        return {
            label: perPage + ' ' + __('pagination.page'),
            value: perPage.toString(),
        } as Option;
    });
});

const perPage = computed({
    get: () => props.pagination.meta.per_page.toString(),
    set: (value: string) => props.filter.onPerPageChange(parseInt(value)),
});
</script>

<template>
    <div
        class="grid gap-3 border-t border-gray-200 px-6 py-4 md:flex md:items-center md:justify-between dark:border-neutral-700"
    >
        <div class="inline-flex items-center gap-x-2">
            <p class="text-sm text-gray-600 dark:text-neutral-400">{{ __('pagination.showing') }}:</p>

            <InputLabel for="per-page-select" class="sr-only" :value="__('pagination.page')" />
            <Select
                id="per-page-select"
                v-model="perPage"
                :placeholder="
                    __('global.choose', {
                        attribute: __('pagination.page'),
                    })
                "
                :options="perPages"
                class="w-fit"
                :searchable="false"
            />

            <p class="text-sm whitespace-nowrap text-gray-600 dark:text-neutral-400">
                {{ countLabel }}
            </p>
        </div>

        <nav v-if="pagination.meta.links.length > 3" class="flex items-center -space-x-px" aria-label="Pagination">
            <Component
                preserve-scroll
                :is="pagination.links.prev ? Link : 'span'"
                :href="pagination.links.prev ?? ''"
                class="inline-flex min-h-9.5 min-w-9.5 items-center justify-center gap-x-1.5 border border-gray-200 px-2.5 py-2 text-sm text-gray-800 first:rounded-s-lg last:rounded-e-lg hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
                aria-label="Previous"
            >
                <SvgIcon name="icons/arrow-left" class="size-3.5 shrink-0 rtl:rotate-180" />
                <span class="hidden sm:block">
                    {{ __('pagination.previous') }}
                </span>
            </Component>
            <template v-for="pagination in filteredLinks" :key="pagination.label">
                <Component
                    preserve-scroll
                    :is="pagination.active || !pagination.url ? 'span' : Link"
                    :href="pagination.url ?? '#'"
                    :disabled="pagination.active"
                    class="flex min-h-9.5 min-w-9.5 items-center justify-center border border-gray-200 px-3 py-2 text-sm text-gray-800 first:rounded-s-lg last:rounded-e-lg focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700"
                    :class="{
                        'bg-gray-200 focus:bg-gray-300 dark:bg-neutral-600 dark:text-white dark:focus:bg-neutral-500':
                            pagination.active,
                        'hover:bg-gray-100 focus:bg-gray-100 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10':
                            !pagination.active,
                    }"
                    aria-current="page"
                >
                    {{ pagination.label }}
                </Component>
            </template>
            <Component
                preserve-scroll
                :is="pagination.links.next ? Link : 'span'"
                :href="pagination.links.next ?? ''"
                class="inline-flex min-h-9.5 min-w-9.5 items-center justify-center gap-x-1.5 border border-gray-200 px-2.5 py-2 text-sm text-gray-800 first:rounded-s-lg last:rounded-e-lg hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
                aria-label="Next"
            >
                <span class="hidden sm:block">
                    {{ __('pagination.next') }}
                </span>
                <SvgIcon name="icons/arrow-right" class="size-3.5 shrink-0 rtl:rotate-180" />
            </Component>
        </nav>
    </div>
</template>
