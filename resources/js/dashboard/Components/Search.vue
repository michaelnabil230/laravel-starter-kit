<script setup lang="ts">
import { SearchResponse } from '@/dashboard/types/search';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { throttle } from 'lodash';
import { ExtendedKeyboardEvent } from 'mousetrap';
import { HSOverlay, ICollectionItem } from 'preline/preline';
import { nextTick, onBeforeUnmount, onMounted, onUnmounted, Ref, ref } from 'vue';

const input = ref<string>('');

const loading = ref<boolean>(false);

const searchResults = ref<SearchResponse>({});

const modal: Ref = ref<Element>();

const onInput = (event: Event) => {
    loading.value = true;
    searchRequest();
};

const searchRequest = throttle(() => {
    axios
        .get<SearchResponse>(route('dashboard.search'), {
            params: { search: input.value },
        })
        .then((response) => (searchResults.value = response.data))
        .finally(() => (loading.value = false));
}, 3000);

onUnmounted(() => {
    HSOverlay.close(modal.value);
    dashboardApp.disableShortcut('mod+/');
});

onBeforeUnmount(() => {
    const hsOverlay = HSOverlay.getInstance(modal.value, true) as ICollectionItem<HSOverlay>;
    hsOverlay.element.close(true);
    hsOverlay.element.destroy();
});

onMounted(() => {
    dashboardApp.addShortcut('mod+/', openOnPress);

    nextTick(() => {
        HSOverlay.on('close', modal.value, () => {
            input.value = '';
            searchResults.value = {};
            loading.value = false;
        });
    });
});

const openOnPress = (event: ExtendedKeyboardEvent, combo: string) => HSOverlay.open(modal.value);
</script>

<template>
    <div
        id="global-search"
        ref="modal"
        class="hs-overlay pointer-events-none fixed start-0 top-0 z-[80] hidden size-full overflow-y-auto overflow-x-hidden hs-overlay-backdrop-open:backdrop-blur-md dark:hs-overlay-backdrop-open:bg-neutral-900/30"
    >
        <div
            class="m-3 flex h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] items-center opacity-0 transition-all ease-out hs-overlay-open:opacity-100 hs-overlay-open:duration-500 sm:mx-auto sm:w-full sm:max-w-lg"
        >
            <div
                class="pointer-events-auto relative flex max-h-full w-full flex-col rounded-xl bg-white shadow-[0_10px_40px_10px_rgba(0,0,0,0.08)] dark:bg-neutral-800 dark:shadow-[0_10px_40px_10px_rgba(0,0,0,0.2)]"
            >
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 start-0 z-20 flex items-center ps-3.5">
                        <svg
                            class="flex-shrink-0 text-gray-400 size-4 dark:text-white/60"
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
                        @input="onInput"
                        v-model="input"
                        id="global-search-input"
                        class="block w-full p-3 text-sm bg-white border-transparent rounded-t-lg border-b-gray-200 ps-10 focus:border-transparent focus:border-b-gray-200 focus:outline-none focus:ring-0 disabled:pointer-events-none disabled:opacity-50 dark:border-transparent dark:border-b-neutral-700 dark:bg-neutral-800 dark:text-neutral-400 dark:placeholder:text-neutral-400"
                        :placeholder="__('global.search.global.label')"
                        autofocus
                    />
                </div>

                <div
                    class="h-[500px] overflow-hidden overflow-y-auto p-4 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 [&::-webkit-scrollbar-track]:bg-gray-100 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 [&::-webkit-scrollbar]:w-2"
                >
                    <template v-if="!loading" v-for="(searchResult, key, index) in searchResults">
                        <div
                            :class="{
                                'mb-4 border-b border-gray-200 pb-4 dark:border-neutral-700':
                                    index == Object.keys(searchResults).length,
                            }"
                        >
                            <span class="block mb-2 text-xs text-gray-500 capitalize dark:text-neutral-500">
                                {{ __('global.search.global.resources.' + key) }}
                                ({{ searchResult.length }})
                            </span>

                            <!-- List Group -->
                            <ul class="-mx-2.5">
                                <template v-for="result in searchResult">
                                    <li>
                                        <Link
                                            :href="result.url"
                                            class="flex items-center px-3 py-2 rounded-lg gap-x-3 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
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

                    <div v-if="loading" class="flex items-center justify-center h-full">
                        <div
                            class="inline-block size-6 animate-spin rounded-full border-[3px] border-current border-t-transparent text-blue-700 dark:text-blue-500"
                            role="status"
                            aria-label="loading"
                        >
                            <span class="sr-only"> {{ __('global.loading') }}... </span>
                        </div>
                    </div>

                    <div
                        v-if="!loading && Object.keys(searchResults).length <= 0"
                        class="flex items-center justify-center h-full text-sm text-gray-800 dark:text-neutral-200"
                    >
                        {{ __('global.not_found.results') }}
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 border-t border-gray-200 dark:border-neutral-700">
                    <div class="inline-flex items-center gap-x-2">
                        <div
                            class="flex flex-col items-center justify-center text-xs text-gray-400 uppercase bg-white border border-gray-200 rounded shadow-sm size-5 dark:border-neutral-700 dark:bg-neutral-800"
                        >
                            <svg
                                class="flex-shrink-0 text-gray-400 size-3 dark:text-neutral-500"
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
        </div>
    </div>
</template>
