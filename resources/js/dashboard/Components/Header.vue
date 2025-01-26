<script setup lang="ts">
import { useAppearance } from '@/dashboard/composables/useAppearance';
import useInitialSharedData from '@/dashboard/composables/useInitialSharedData';
import { Link } from '@inertiajs/vue3';
import {
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuPortal,
    DropdownMenuRoot,
    DropdownMenuTrigger,
} from 'reka-ui';
import Flag from './Flag.vue';
import GlobalSearch from './GlobalSearch.vue';

const initialSharedData = useInitialSharedData();

const locale = initialSharedData('locale');

const supportedLocales = initialSharedData('supportedLocales');

const currentLocale = supportedLocales[initialSharedData('locale')];

const isSelected = (localeKey: string): boolean => locale === localeKey;

const IMAGE_MAP: Record<string, string> = {
    en: 'US',
    ar: 'SA',
};

const { updateAppearance, appearance } = useAppearance();
</script>

<template>
    <header
        class="fixed inset-x-0 top-0 z-50 flex flex-wrap border-b border-gray-200 bg-white md:flex-nowrap md:justify-start lg:ms-[260px] dark:border-neutral-700 dark:bg-neutral-800"
    >
        <div
            class="flex w-full basis-full items-center justify-between px-2 py-2.5 sm:px-5 xl:grid xl:grid-cols-3"
            aria-label="Global"
        >
            <div class="flex items-center md:gap-x-3 xl:col-span-1">
                <div id="button-sidebar" />

                <GlobalSearch />
            </div>

            <div class="flex items-center justify-end gap-x-2 xl:col-span-2">
                <div id="button-global-search-sm" />

                <!-- Start Language Dropdown -->
                <DropdownMenuRoot>
                    <DropdownMenuTrigger
                        class="inline-flex cursor-pointer items-center gap-x-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 shadow-2xs hover:bg-gray-50 focus:bg-gray-100 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    >
                        <Flag :country="IMAGE_MAP[locale]" />

                        {{ currentLocale.native }}
                    </DropdownMenuTrigger>

                    <DropdownMenuPortal>
                        <DropdownMenuContent
                            class="duration z-100 w-48 space-y-1 rounded-xl bg-white p-2 opacity-0 shadow-[0_10px_40px_10px_rgba(0,0,0,0.08)] transition-[opacity,margin] data-[state=open]:opacity-100 dark:bg-neutral-900 dark:shadow-[0_10px_40px_10px_rgba(0,0,0,0.2)]"
                        >
                            <DropdownMenuItem
                                :key="localeKey"
                                v-for="(locale, localeKey) in supportedLocales"
                                :class="{
                                    'bg-gray-100 dark:bg-neutral-800': isSelected(localeKey as string),
                                }"
                                class="w-full cursor-pointer rounded-lg px-4 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                            >
                                <a :href="locale.url" class="flex items-center gap-x-2">
                                    <Flag :country="IMAGE_MAP[localeKey]" />

                                    <div class="text-gray-800 dark:text-neutral-200">
                                        {{ locale.native }}
                                    </div>
                                    <span v-if="isSelected(localeKey as string)" class="ms-auto">
                                        <svg
                                            class="size-3.5 shrink-0 text-gray-800 dark:text-neutral-200"
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
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </span>
                                </a>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenuPortal>
                </DropdownMenuRoot>
                <!-- End Language Dropdown -->

                <div class="mx-1.5 h-6 w-px border-e border-gray-200 dark:border-neutral-700"></div>

                <DropdownMenuRoot>
                    <DropdownMenuTrigger type="button" class="cursor-pointer">
                        <img
                            class="size-[38px] rounded-full object-cover"
                            :src="$page.props.auth.admin.profile_photo_url"
                        />
                    </DropdownMenuTrigger>
                    <DropdownMenuPortal>
                        <DropdownMenuContent
                            align="end"
                            class="duration z-100 w-60 rounded-xl bg-white opacity-0 shadow-[0_10px_40px_10px_rgba(0,0,0,0.08)] transition-[opacity,margin] data-[state=open]:opacity-100 dark:bg-neutral-900 dark:shadow-[0_10px_40px_10px_rgba(0,0,0,0.2)]"
                        >
                            <div class="border-b border-gray-200 p-1 dark:border-neutral-800">
                                <div
                                    class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-300 dark:hover:bg-neutral-800"
                                >
                                    <img
                                        class="size-8 shrink-0 rounded-full object-cover"
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
                                        @click="updateAppearance('light')"
                                        type="button"
                                        class="-ms-px inline-flex w-full cursor-pointer items-center justify-center gap-2 border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 first:ms-0 first:rounded-s-lg last:rounded-e-lg hover:bg-gray-50 focus:z-10 focus:bg-gray-50 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                                        :class="{
                                            '!text-blue-700 dark:!text-blue-500': appearance === 'light',
                                        }"
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
                                            <circle cx="12" cy="12" r="4" />
                                            <path d="M12 2v2" />
                                            <path d="M12 20v2" />
                                            <path d="m4.93 4.93 1.41 1.41" />
                                            <path d="m17.66 17.66 1.41 1.41" />
                                            <path d="M2 12h2" />
                                            <path d="M20 12h2" />
                                            <path d="m6.34 17.66-1.41 1.41" />
                                            <path d="m19.07 4.93-1.41 1.41" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="updateAppearance('dark')"
                                        type="button"
                                        class="-ms-px inline-flex w-full cursor-pointer items-center justify-center gap-2 border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 first:ms-0 first:rounded-s-lg last:rounded-e-lg hover:bg-gray-50 focus:z-10 focus:bg-gray-50 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                                        :class="{
                                            '!text-blue-700 dark:!text-blue-500': appearance === 'dark',
                                        }"
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
                                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="updateAppearance('system')"
                                        type="button"
                                        class="-ms-px inline-flex w-full cursor-pointer items-center justify-center gap-2 border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 first:ms-0 first:rounded-s-lg last:rounded-e-lg hover:bg-gray-50 focus:z-10 focus:bg-gray-50 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                                        :class="{
                                            '!text-blue-700 dark:!text-blue-500': appearance === 'system',
                                        }"
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
                                            />
                                            <path
                                                d="M16 22C14.8233 21.364 13.4571 21 12 21C10.5429 21 9.17669 21.364 8 22"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="p-1">
                                <Link
                                    :href="route('dashboard.profile.edit')"
                                    class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
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
                                    class="flex w-full cursor-pointer items-center gap-x-3 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
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
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                        <polyline points="16 17 21 12 16 7" />
                                        <line x1="21" x2="9" y1="12" y2="12" />
                                    </svg>
                                    {{ __('logout') }}
                                </Link>
                            </div>
                        </DropdownMenuContent>
                    </DropdownMenuPortal>
                </DropdownMenuRoot>
            </div>
        </div>
    </header>
</template>
