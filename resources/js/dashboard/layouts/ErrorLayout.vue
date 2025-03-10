<script setup lang="ts">
import ButtonLink from '@/dashboard/components/button/ButtonLink.vue';
import { Head } from '@inertiajs/vue3';
import App from './App.vue';

defineProps<{
    title?: string;
}>();
</script>

<template>
    <App>
        <Head :title="title" />

        <div class="mx-auto flex size-full h-screen max-w-[50rem] flex-col">
            <header class="z-50 mb-auto flex w-full justify-center py-4">
                <nav class="px-4 sm:px-6 lg:px-8" aria-label="Global">
                    <a class="flex-none text-xl font-semibold dark:text-white sm:text-3xl" href="#">
                        {{ config('appName') }}
                    </a>
                </nav>
            </header>

            <main id="content">
                <div class="px-4 py-10 text-center sm:px-6 lg:px-8">
                    <slot />

                    <div class="mt-5 flex flex-col items-center justify-center gap-2 sm:flex-row sm:gap-3">
                        <ButtonLink :href="route('dashboard.welcome')">
                            <svg
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
                                <path d="m15 18-6-6 6-6" />
                            </svg>
                            {{ __('back_to_home') }}
                        </ButtonLink>
                    </div>
                </div>
            </main>

            <footer class="mt-auto py-5 text-center">
                <div class="mx-auto max-w-[85rem] px-4 sm:px-6 lg:px-8">
                    <p class="text-sm text-gray-500 dark:text-neutral-500">
                        {{ config('appName') }} © {{ __('all_rights_reserved') }}. {{ new Date().getFullYear() }}.
                    </p>
                </div>
            </footer>
        </div>
    </App>
</template>
