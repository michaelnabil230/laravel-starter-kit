<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
import { useAppearance } from '@/dashboard/composables/useAppearance';
import initialSharedData from '@/dashboard/mixins/initialSharedData';
import { Link } from '@inertiajs/vue3';
import {
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuPortal,
    DropdownMenuRoot,
    DropdownMenuTrigger,
} from 'reka-ui';
import Flag from './Flag.vue';
import NameCircle from './NameCircle/NameCircle.vue';

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
        class="fixed inset-x-0 top-0 z-50 flex flex-wrap border-b border-gray-200 bg-white md:flex-nowrap md:justify-start lg:ms-65 dark:border-neutral-700 dark:bg-neutral-800"
    >
        <div
            class="flex w-full basis-full items-center justify-between px-2 py-2.5 sm:px-5 xl:grid xl:grid-cols-3"
            aria-label="Global"
        >
            <div class="flex items-center xl:col-span-1">
                <div id="button-sidebar" />
            </div>

            <div class="flex items-center justify-end gap-x-2 xl:col-span-2">
                <DropdownMenuRoot>
                    <DropdownMenuTrigger
                        class="inline-flex cursor-pointer items-center gap-x-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-50 focus:bg-gray-100 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
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
                                        <SvgIcon
                                            name="icons/check"
                                            class="size-3.5 shrink-0 text-gray-800 dark:text-neutral-200"
                                        />
                                    </span>
                                </a>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenuPortal>
                </DropdownMenuRoot>

                <div class="mx-1.5 h-6 w-px border-e border-gray-200 dark:border-neutral-700"></div>

                <DropdownMenuRoot>
                    <DropdownMenuTrigger type="button" class="cursor-pointer">
                        <NameCircle :name="$page.props.auth.admin.name" />
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
                                    <NameCircle :name="$page.props.auth.admin.name" size="small" />

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
                                            'text-blue-700! dark:text-blue-500!': appearance === 'light',
                                        }"
                                    >
                                        <SvgIcon name="icons/sun" class="size-4 shrink-0" />
                                    </button>
                                    <button
                                        @click="updateAppearance('dark')"
                                        type="button"
                                        class="-ms-px inline-flex w-full cursor-pointer items-center justify-center gap-2 border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 first:ms-0 first:rounded-s-lg last:rounded-e-lg hover:bg-gray-50 focus:z-10 focus:bg-gray-50 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                                        :class="{
                                            'text-blue-700! dark:text-blue-500!': appearance === 'dark',
                                        }"
                                    >
                                        <SvgIcon name="icons/moon" class="size-4 shrink-0" />
                                    </button>
                                    <button
                                        @click="updateAppearance('system')"
                                        type="button"
                                        class="-ms-px inline-flex w-full cursor-pointer items-center justify-center gap-2 border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 first:ms-0 first:rounded-s-lg last:rounded-e-lg hover:bg-gray-50 focus:z-10 focus:bg-gray-50 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                                        :class="{
                                            'text-blue-700! dark:text-blue-500!': appearance === 'system',
                                        }"
                                    >
                                        <SvgIcon name="icons/monitor" class="size-4 shrink-0" />
                                    </button>
                                </div>
                            </div>
                            <div class="p-1">
                                <Link
                                    :href="route('dashboard.profile.edit')"
                                    class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                                >
                                    <SvgIcon name="icons/user" class="size-4 shrink-0" />
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
                                    <SvgIcon name="icons/logout" class="size-4 shrink-0" />
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
