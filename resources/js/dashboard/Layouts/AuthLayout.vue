<script setup lang="ts">
import { useAppearance } from '@/dashboard/composables/useAppearance';
import { Head } from '@inertiajs/vue3';
import App from './App.vue';

defineProps<{
    title?: string;
}>();

const { appearance, updateAppearance } = useAppearance();
</script>

<template>
    <App>
        <Head :title="title" />

        <main class="mx-auto flex h-full min-h-screen flex-col justify-center space-y-5 px-5 sm:w-[448px]">
            <div>
                <h1 class="text-xl font-semibold text-gray-800 sm:text-2xl dark:text-neutral-200">
                    <slot name="title" />
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">
                    <slot name="description" />
                </p>
            </div>
            <slot />
        </main>

        <footer
            class="relative inset-x-0 bottom-0 mt-5 h-10 border-t border-gray-200 bg-white sm:h-16 dark:border-neutral-700 dark:bg-neutral-900"
        >
            <div
                class="mx-auto flex w-full max-w-5xl items-center justify-between p-2 px-4 py-6 sm:p-5 sm:px-6 lg:px-8"
            >
                <p class="text-xs text-gray-500 sm:text-sm dark:text-neutral-500">
                    {{ initialSharedData('appName') }} © {{ __('all_rights_reserved') }}.
                    {{ new Date().getFullYear() }}.
                </p>

                <div class="flex items-center gap-x-2">
                    <button
                        @click="updateAppearance('system')"
                        type="button"
                        class="flex cursor-pointer items-center gap-x-1.5 text-sm text-gray-500 hover:text-gray-800 focus:text-gray-800 focus:outline-hidden dark:text-neutral-500 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
                        :class="{ '!text-gray-800 dark:!text-white': appearance === 'system' }"
                    >
                        <svg
                            class="size-4 shrink-0"
                            xmlns="http://www.w3.org/2000/svg"
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
                            <path d="M16 22C14.8233 21.364 13.4571 21 12 21C10.5429 21 9.17669 21.364 8 22" />
                        </svg>
                        <span class="sr-only">System mode</span>
                    </button>
                    <button
                        @click="updateAppearance('dark')"
                        type="button"
                        class="flex cursor-pointer items-center gap-x-1.5 text-sm text-gray-500 hover:text-gray-800 focus:text-gray-800 focus:outline-hidden dark:text-neutral-500 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
                        :class="{ '!text-gray-800 dark:!text-white': appearance === 'dark' }"
                    >
                        <svg
                            class="size-4 shrink-0"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                        </svg>
                        <span class="sr-only">Dark mode</span>
                    </button>
                    <button
                        @click="updateAppearance('light')"
                        type="button"
                        class="flex cursor-pointer items-center gap-x-1.5 text-sm text-gray-500 hover:text-gray-800 focus:text-gray-800 focus:outline-hidden dark:text-neutral-500 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
                        :class="{ '!text-gray-800 dark:!text-white': appearance === 'light' }"
                    >
                        <svg
                            class="size-4 shrink-0"
                            xmlns="http://www.w3.org/2000/svg"
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
                        <span class="sr-only">Light mode</span>
                    </button>
                </div>
            </div>
        </footer>
    </App>
</template>
