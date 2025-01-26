<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

defineProps<{
    title?: string;
}>();

onMounted(() => dashboardApp.preline());
</script>

<template>
    <Head :title="title" />

    <main class="mx-auto flex h-full min-h-screen flex-col justify-center space-y-5 px-5 sm:w-[448px]">
        <div>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-neutral-200 sm:text-2xl">
                <slot name="title" />
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">
                <slot name="description" />
            </p>
        </div>
        <slot />
    </main>

    <footer
        class="relative inset-x-0 bottom-0 mt-5 h-10 border-t border-gray-200 bg-white dark:border-neutral-700 dark:bg-neutral-900 sm:h-16"
    >
        <div class="mx-auto flex w-full max-w-5xl items-center justify-between p-2 px-4 py-6 sm:p-5 sm:px-6 lg:px-8">
            <p class="text-xs text-gray-500 dark:text-neutral-500 sm:text-sm">
                {{ config('appName') }} © {{ __('all_rights_reserved') }}. {{ new Date().getFullYear() }}.
            </p>

            <div>
                <!-- Dark Mode -->
                <button
                    type="button"
                    class="hs-dark-mode flex items-center gap-x-1.5 text-sm text-gray-500 hover:text-gray-800 focus:text-gray-800 focus:outline-none hs-dark-mode-active:hidden dark:text-neutral-500 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
                    data-hs-theme-click-value="dark"
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
                    <span class="sr-only">Dark mode</span>
                </button>
                <button
                    type="button"
                    class="hs-dark-mode hidden items-center gap-x-1.5 text-sm text-gray-500 hover:text-gray-800 focus:text-gray-800 focus:outline-none hs-dark-mode-active:flex dark:text-neutral-500 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
                    data-hs-theme-click-value="light"
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
                    <span class="sr-only">Light mode</span>
                </button>
                <!-- End Dark Mode -->
            </div>
        </div>
    </footer>
</template>
