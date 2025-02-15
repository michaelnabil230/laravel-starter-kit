<script setup lang="ts">
import useConfig from '@/dashboard/composables/useConfig';
import { Locale, Locales } from '@/dashboard/types/appConfig';
import { Link } from '@inertiajs/vue3';

const { config } = useConfig();

const locale: string = config('locale');

const supportedLocales: Locales = config('supportedLocales');

const currentLocale: Locale = supportedLocales[config<string>('locale')];

const isSelected = (localeKey: string): boolean => locale === localeKey;

const getImageUrl = (key: string): string => new URL(`../../../images/flags/${key}.svg`, import.meta.url).href;
</script>

<template>
    <header
        class="fixed inset-x-0 top-0 z-50 flex flex-wrap border-b border-gray-200 bg-white dark:border-neutral-700 dark:bg-neutral-800 md:flex-nowrap md:justify-start lg:ms-[260px]"
    >
        <div
            class="flex w-full basis-full items-center justify-between px-2 py-2.5 sm:px-5 xl:grid xl:grid-cols-3"
            aria-label="Global"
        >
            <div class="flex items-center md:gap-x-3 xl:col-span-1">
                <div class="lg:hidden">
                    <button
                        type="button"
                        class="inline-flex h-[38px] w-7 items-center justify-center gap-x-2 rounded-lg border border-gray-200 bg-white text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 focus:bg-gray-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                        data-hs-overlay="#sidebar"
                        aria-controls="sidebar"
                        aria-label="Toggle navigation"
                    >
                        <svg
                            class="size-4 flex-shrink-0"
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
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </button>
                </div>

                <div class="hidden min-w-80 lg:block xl:w-full">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 start-0 z-20 flex items-center ps-3.5">
                            <svg
                                class="size-4 flex-shrink-0 text-gray-400 dark:text-white/60"
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
                            class="block w-full rounded-lg border-gray-200 bg-white py-2 pe-16 ps-10 text-sm focus:border-gray-200 focus:outline-none focus:ring-0 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-400 dark:placeholder:text-neutral-400 dark:focus:ring-neutral-600"
                            :placeholder="__('global.search.global.label')"
                            data-hs-overlay="#global-search"
                        />
                        <div
                            class="pointer-events-none absolute inset-y-0 end-0 z-20 flex items-center pe-3 text-gray-400"
                        >
                            <svg
                                class="size-3 flex-shrink-0 text-gray-400 dark:text-white/60"
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
                                    class="size-3 flex-shrink-0 text-gray-400 dark:text-white/60"
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
            </div>

            <div class="flex items-center justify-end gap-x-2 xl:col-span-2">
                <div class="lg:hidden">
                    <button
                        type="button"
                        class="inline-flex size-[38px] flex-shrink-0 items-center justify-center gap-x-2 rounded-full text-gray-500 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none dark:text-neutral-400 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                        data-hs-overlay="#global-search"
                    >
                        <svg
                            class="size-4 flex-shrink-0"
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
                </div>

                <!-- Start Language Dropdown -->
                <div class="hs-dropdown relative inline-flex [--auto-close:inside] [--placement:bottom-right]">
                    <button
                        id="language-dropdown"
                        type="button"
                        class="inline-flex items-center gap-x-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 shadow-sm hover:bg-gray-50 focus:bg-gray-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    >
                        <div class="size-4 overflow-hidden rounded-full">
                            <img :src="getImageUrl(locale)" class="h-full w-full object-cover" />
                        </div>

                        {{ currentLocale.native }}
                    </button>

                    <div
                        class="hs-dropdown-menu duration z-10 hidden w-48 rounded-xl bg-white opacity-0 shadow-[0_10px_40px_10px_rgba(0,0,0,0.08)] transition-[opacity,margin] hs-dropdown-open:opacity-100 dark:bg-neutral-900 dark:shadow-[0_10px_40px_10px_rgba(0,0,0,0.2)]"
                        aria-labelledby="language-dropdown"
                    >
                        <div class="p-1">
                            <div
                                :key="localeKey"
                                v-for="(locale, localeKey) in supportedLocales"
                                :class="{
                                    'selected hs-selected:bg-gray-100 dark:hs-selected:bg-neutral-800': isSelected(
                                        localeKey as string,
                                    ),
                                }"
                                class="w-full cursor-pointer rounded-lg px-4 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                            >
                                <a :href="locale.url" class="flex items-center gap-x-2">
                                    <div class="size-4 overflow-hidden rounded-full">
                                        <img
                                            :src="getImageUrl(localeKey as string)"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>

                                    <div class="text-gray-800 dark:text-neutral-200">
                                        {{ locale.native }}
                                    </div>
                                    <span
                                        v-if="isSelected(localeKey as string)"
                                        class="ms-auto hidden hs-selected:block"
                                    >
                                        <svg
                                            class="size-3.5 flex-shrink-0 text-gray-800 dark:text-neutral-200"
                                            xmlns="http:.w3.org/2000/svg"
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
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Language Dropdown -->

                <div class="mx-1.5 h-6 w-px border-e border-gray-200 dark:border-neutral-700"></div>

                <div class="h-[38px]">
                    <div class="hs-dropdown relative inline-flex">
                        <button
                            id="user-dropdown"
                            type="button"
                            class="inline-flex flex-shrink-0 items-center gap-x-3 rounded-full text-start focus:bg-gray-100 focus:outline-none dark:focus:bg-neutral-700"
                        >
                            <img
                                class="size-[38px] flex-shrink-0 rounded-full object-cover"
                                :src="$page.props.auth.admin.profile_photo_url"
                            />
                        </button>
                        <div
                            class="hs-dropdown-menu duration z-10 hidden w-60 rounded-xl bg-white opacity-0 shadow-[0_10px_40px_10px_rgba(0,0,0,0.08)] transition-[opacity,margin] hs-dropdown-open:opacity-100 dark:bg-neutral-900 dark:shadow-[0_10px_40px_10px_rgba(0,0,0,0.2)]"
                            aria-labelledby="user-dropdown"
                        >
                            <div class="border-b border-gray-200 p-1 dark:border-neutral-800">
                                <div
                                    class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-300 dark:hover:bg-neutral-800"
                                >
                                    <img
                                        class="size-8 flex-shrink-0 rounded-full object-cover"
                                        :src="$page.props.auth.admin.profile_photo_url"
                                    />

                                    <span class="text-sm font-semibold text-gray-800 dark:text-neutral-300">
                                        {{ $page.props.auth.admin.name }}
                                    </span>
                                </div>
                            </div>
                            <div class="border-b border-gray-200 px-1 py-2.5 dark:border-neutral-800">
                                <div class="inline-flex w-full rounded-lg">
                                    <button
                                        type="button"
                                        data-hs-theme-click-value="light"
                                        class="hs-dark-mode -ms-px inline-flex w-full items-center justify-center gap-2 border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 first:ms-0 first:rounded-s-lg last:rounded-e-lg hover:bg-gray-50 focus:z-10 focus:bg-gray-50 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
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
                                            <circle cx="12" cy="12" r="4"></circle>
                                            <path d="M12 2v2"></path>
                                            <path d="M12 20v2"></path>
                                            <path d="m4.93 4.93 1.41 1.41"></path>
                                            <path d="m17.66 17.66 1.41 1.41"></path>
                                            <path d="M2 12h2"></path>
                                            <path d="M20 12h2"></path>
                                            <path d="m6.34 17.66-1.41 1.41"></path>
                                            <path d="m19.07 4.93-1.41 1.41"></path>
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        data-hs-theme-click-value="dark"
                                        class="hs-dark-mode -ms-px inline-flex w-full items-center justify-center gap-2 border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 first:ms-0 first:rounded-s-lg last:rounded-e-lg hover:bg-gray-50 focus:z-10 focus:bg-gray-50 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
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
                                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        data-hs-theme-click-value="auto"
                                        class="hs-dark-mode -ms-px inline-flex w-full items-center justify-center gap-2 border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 first:ms-0 first:rounded-s-lg last:rounded-e-lg hover:bg-gray-50 focus:z-10 focus:bg-gray-50 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
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
                                            <path
                                                d="M19 2H5C3.34315 2 2 3.34315 2 5V15C2 16.6569 3.34315 18 5 18H19C20.6569 18 22 16.6569 22 15V5C22 3.34315 20.6569 2 19 2Z"
                                                stroke-width="1.5"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M16 22C14.8233 21.364 13.4571 21 12 21C10.5429 21 9.17669 21.364 8 22"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="p-1">
                                <Link
                                    :href="route('dashboard.profile.edit')"
                                    class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                                >
                                    <svg
                                        class="size-4 flex-shrink-0"
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
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                    {{ __('profile.name') }}
                                </Link>
                            </div>
                            <div class="p-1">
                                <Link
                                    :href="route('dashboard.logout')"
                                    method="post"
                                    as="button"
                                    class="flex w-full items-center gap-x-3 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                                >
                                    <svg
                                        class="size-4 flex-shrink-0"
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
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" x2="9" y1="12" y2="12"></line>
                                    </svg>
                                    {{ __('logout') }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>
