<script setup lang="ts">
import useLocalization from '@/dashboard/composables/useLocalization';
import { App } from '@/dashboard/types/models';
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const { __ } = useLocalization();

const emit = defineEmits(['perPage']);

const props = defineProps<{
    pagination: App.ApiPaginate<any>;
}>();

const perPageSelected = ref<number>(props.pagination.meta.per_page);

const filteredLinks = computed(() => {
    return props.pagination.meta.links.slice(1, -1);
});

const perPages = [15, 25, 50, 75, 100];

const onchangePerPage = (value: number) => {
    perPageSelected.value = value;
    emit('perPage', value.toString());
};

const resourceCountLabel = computed(() => {
    const first = props.pagination.meta.per_page * (props.pagination.meta.current_page - 1);

    return `${first + 1}-${first + props.pagination.meta.total} ${__('pagination.of')} ${props.pagination.meta.total}`;
});
</script>

<template>
    <div
        class="grid gap-3 border-t border-gray-200 px-6 py-4 dark:border-neutral-700 md:flex md:items-center md:justify-between"
    >
        <div class="inline-flex items-center gap-x-2">
            <p class="text-sm text-gray-600 dark:text-neutral-400">{{ __('pagination.showing') }}:</p>
            <div class="hs-dropdown relative inline-flex [--placement:top-left]">
                <button
                    id="hs-default-bordered-pagination-dropdown"
                    type="button"
                    class="hs-dropdown-toggle inline-flex items-center gap-x-1 rounded-lg border border-gray-200 px-2.5 py-2 text-sm text-gray-800 shadow-sm hover:bg-gray-50 focus:bg-gray-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    aria-haspopup="menu"
                    aria-expanded="false"
                    aria-label="Dropdown"
                >
                    {{ perPageSelected }} {{ __('pagination.page') }}
                    <svg
                        class="size-4 shrink-0"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>
                </button>
                <div
                    class="hs-dropdown-menu z-50 mb-2 hidden w-48 space-y-0.5 rounded-lg bg-white p-1 opacity-0 shadow-md transition-[margin,opacity] duration-300 hs-dropdown-open:opacity-100 dark:divide-neutral-700 dark:border dark:border-neutral-700 dark:bg-neutral-800"
                    role="menu"
                    aria-orientation="vertical"
                    aria-labelledby="hs-default-bordered-pagination-dropdown"
                >
                    <button
                        v-for="perPage in perPages"
                        :key="perPage"
                        @click="onchangePerPage(perPage)"
                        class="flex w-full items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
                    >
                        {{ perPage }} {{ __('pagination.page') }}
                        <svg
                            v-if="perPageSelected === perPage"
                            class="ms-auto size-4 shrink-0 text-blue-700 dark:text-blue-500"
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </button>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-neutral-400">
                {{ resourceCountLabel }}
            </p>
        </div>

        <!-- Pagination -->
        <nav v-if="pagination.meta.links.length > 3" class="flex items-center -space-x-px" aria-label="Pagination">
            <Component
                preserve-scroll
                :is="pagination.links.prev ? Link : 'span'"
                :href="pagination.links.prev ?? ''"
                class="inline-flex min-h-[38px] min-w-[38px] items-center justify-center gap-x-1.5 border border-gray-200 px-2.5 py-2 text-sm text-gray-800 first:rounded-s-lg last:rounded-e-lg hover:bg-gray-100 focus:bg-gray-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
                aria-label="Previous"
            >
                <svg
                    class="size-3.5 shrink-0 rtl:rotate-180"
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
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
                    class="flex min-h-[38px] min-w-[38px] items-center justify-center border border-gray-200 px-3 py-2 text-sm text-gray-800 first:rounded-s-lg last:rounded-e-lg focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700"
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
                class="inline-flex min-h-[38px] min-w-[38px] items-center justify-center gap-x-1.5 border border-gray-200 px-2.5 py-2 text-sm text-gray-800 first:rounded-s-lg last:rounded-e-lg hover:bg-gray-100 focus:bg-gray-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
                aria-label="Next"
            >
                <span class="hidden sm:block">
                    {{ __('pagination.next') }}
                </span>
                <svg
                    class="size-3.5 shrink-0 rtl:rotate-180"
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="m9 18 6-6-6-6"></path>
                </svg>
            </Component>
        </nav>
    </div>
</template>
