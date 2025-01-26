<script setup lang="ts">
import useShortcutController from '@/dashboard/composables/useShortcutController';
import { SearchResponse } from '@/dashboard/types/search';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { debounce } from 'lodash';
import { DialogContent, DialogDescription, DialogOverlay, DialogPortal, DialogRoot, DialogTitle } from 'reka-ui';
import { onMounted, ref, watch } from 'vue';
import Loading from './Loading.vue';

const shortcutController = useShortcutController();

const search = ref<string>('');

const loading = ref<boolean>(false);

const searchResults = ref<SearchResponse>({});

const modal = ref(false);

const debouncedSearch = debounce(() => {
    axios
        .get<SearchResponse>(route('dashboard.search'), {
            params: { search: search.value },
        })
        .then((response) => (searchResults.value = response.data))
        .finally(() => (loading.value = false));
}, 3000);

watch(search, (newValue) => {
    if (newValue !== '') {
        loading.value = true;

        debouncedSearch();
    } else {
        searchResults.value = {};
    }
});

watch(modal, (newValue) => {
    if (newValue) {
        search.value = '';
        searchResults.value = {};
    }
});

onMounted(() => shortcutController.addShortcut('mod+/', open));

const open = () => {
    modal.value = true;
    const input = document.getElementById('header-global-search') as HTMLInputElement;
    input.blur();
};
</script>

<template>
    <Teleport defer to="#button-global-search-sm">
        <button
            @click="open"
            type="button"
            class="inline-flex size-[38px] shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-full text-gray-500 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden lg:hidden dark:text-neutral-400 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
        >
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
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
        </button>
    </Teleport>
    <div class="hidden min-w-80 lg:block xl:w-full">
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 start-0 z-20 flex items-center ps-3.5">
                <svg
                    class="size-4 shrink-0 text-gray-400 dark:text-white/60"
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
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
            </div>
            <label for="header-global-search" class="sr-only">
                {{ __('global.search.global.label') }}
            </label>
            <input
                type="text"
                id="header-global-search"
                class="block w-full rounded-lg border-gray-200 bg-white py-2 ps-10 pe-16 text-sm outline-hidden focus:ring-0 focus:outline-none dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-400 dark:placeholder:text-neutral-400"
                :placeholder="__('global.search.global.label')"
                @click.prevent="open"
            />
            <div class="pointer-events-none absolute inset-y-0 end-0 z-20 flex items-center pe-3 text-gray-400">
                <svg
                    class="size-3 shrink-0 text-gray-400 dark:text-white/60"
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
                    <path d="M15 6v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3V6a3 3 0 1 0-3 3h12a3 3 0 1 0-3-3" />
                </svg>
                <span class="mx-1">
                    <svg
                        class="size-3 shrink-0 text-gray-400 dark:text-white/60"
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
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                </span>
                <span class="text-xs">/</span>
            </div>
        </div>
    </div>
    <DialogRoot v-model:open="modal">
        <DialogPortal>
            <DialogOverlay
                class="data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 fixed inset-0 z-60 bg-gray-900/50 backdrop-blur-md dark:bg-neutral-900/80"
            />

            <DialogContent
                class="data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 fixed top-1/2 left-1/2 z-100 w-full max-w-xl -translate-1/2 duration-200"
            >
                <DialogTitle />
                <DialogDescription />

                <div
                    class="pointer-events-auto relative flex max-h-full w-full flex-col rounded-xl bg-white shadow-[0_10px_40px_10px_rgba(0,0,0,0.08)] dark:bg-neutral-800 dark:shadow-[0_10px_40px_10px_rgba(0,0,0,0.2)]"
                >
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 start-0 z-20 flex items-center ps-3.5">
                            <svg
                                class="size-4 shrink-0 text-gray-400 dark:text-white/60"
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
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </div>
                        <label for="global-search-input" class="sr-only">
                            {{ __('global.search.global.label') }}
                        </label>
                        <input
                            type="text"
                            v-model="search"
                            id="global-search-input"
                            class="block w-full rounded-t-lg border-transparent border-b-gray-200 bg-white p-3 ps-10 text-sm focus:border-transparent focus:border-b-gray-200 focus:ring-0 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:border-transparent dark:border-b-neutral-700 dark:bg-neutral-800 dark:text-neutral-400 dark:placeholder:text-neutral-400"
                            :placeholder="__('global.search.global.label')"
                            autofocus
                        />
                    </div>

                    <div
                        class="overflow-hidden overflow-y-auto p-4 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 [&::-webkit-scrollbar-track]:bg-gray-100 dark:[&::-webkit-scrollbar-track]:bg-neutral-700"
                    >
                        <template v-if="!loading">
                            <template v-for="(searchResult, key, index) in searchResults" :key="key">
                                <div
                                    :class="{
                                        'mb-4 border-b border-gray-200 pb-4 dark:border-neutral-700':
                                            index == Object.keys(searchResults).length,
                                    }"
                                >
                                    <span class="mb-2 block text-xs text-gray-500 capitalize dark:text-neutral-500">
                                        {{ __('global.search.global.resources.' + key) }}
                                        ({{ searchResult.length }})
                                    </span>

                                    <!-- List Group -->
                                    <ul class="-mx-2.5">
                                        <template v-for="result in searchResult" :key="result.id">
                                            <li>
                                                <Link
                                                    :href="result.url"
                                                    class="flex items-center gap-x-3 rounded-lg px-3 py-2 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                                                >
                                                    <span class="text-sm text-gray-800 dark:text-neutral-200">
                                                        {{ result.title }}
                                                    </span>
                                                    <span class="text-xs text-gray-400 dark:text-neutral-500">
                                                        {{ result.searchable.created_at }}
                                                    </span>
                                                </Link>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </template>
                        </template>

                        <Loading v-if="loading" />

                        <div
                            v-if="!loading && Object.keys(searchResults).length <= 0"
                            class="flex h-full items-center justify-center text-sm text-gray-800 dark:text-neutral-200"
                        >
                            {{ __('global.not_found.results') }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-gray-200 p-4 dark:border-neutral-700">
                        <div class="inline-flex items-center gap-x-2">
                            <div
                                class="flex size-5 flex-col items-center justify-center rounded-xs border border-gray-200 bg-white text-xs text-gray-400 uppercase shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
                            >
                                <svg
                                    class="size-3 shrink-0 text-gray-400 dark:text-neutral-500"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.6"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path
                                        d="M22 14.596C21.828 15.1189 20.888 16 19.5969 16C18.3057 16 16.9265 14.6624 16.9265 12.8974V12.0687C16.9265 10.2373 18.2022 9.00142 19.5969 9.00142C20.9918 9.00142 21.7942 9.69876 22 10.5666M14.5417 10.3109C14.3233 9.6198 13.3292 8.96537 12.1831 9.00142C11.0374 9.03732 10.0119 9.83333 10.0119 10.777C10.0119 11.7208 10.6901 12.0632 12.1839 12.2081C13.6774 12.353 14.49 13.1331 14.5417 13.9596C14.5934 14.7861 13.8083 16 12.1839 16C10.7604 16 9.69525 14.6894 9.63548 13.9379M7.25295 14.7213C6.82726 15.5884 5.94455 15.9999 4.75814 15.9999C3.57172 15.9999 2 15.088 2 12.7831V12.1113C2 10.5911 3.16477 9.00113 4.75814 9.00113C6.35166 9.00113 7.41158 10.5059 7.25295 12.2838H2.47754"
                                    />
                                </svg>
                            </div>
                            <span class="text-xs text-gray-400 dark:text-neutral-500">
                                {{ __('global.closure.to_close') }}
                            </span>
                        </div>
                    </div>
                </div>
            </DialogContent>
        </DialogPortal>
    </DialogRoot>
</template>
