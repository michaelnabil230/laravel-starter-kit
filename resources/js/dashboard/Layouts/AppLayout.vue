<script setup lang="ts">
import Breadcrumbs from '@/dashboard/Components/Breadcrumbs.vue';
import Header from '@/dashboard/Components/Header.vue';
import Modal from '@/dashboard/Components/Modal';
import Sidebar from '@/dashboard/Components/Sidebar.vue';
import Toasts from '@/dashboard/Components/Toasts/Index.vue';
import { Head } from '@inertiajs/vue3';
import App from './App.vue';

defineProps<{
    title?: string;
    breadcrumbs?: Breadcrumbs;
}>();
</script>

<template>
    <App>
        <Head :title="title" />

        <Header />

        <Sidebar />

        <Toasts />

        <Modal />

        <main class="h-full min-h-screen pt-[59px] lg:ms-[260px]">
            <div class="space-y-5 p-2 sm:p-5 sm:py-0 md:pt-5">
                <Breadcrumbs v-if="breadcrumbs" :breadcrumbs="breadcrumbs" />
                <slot />
            </div>
        </main>

        <footer
            class="relative inset-x-0 bottom-0 mt-5 h-10 border-t border-gray-200 bg-white sm:h-16 lg:ms-[260px] dark:border-neutral-700 dark:bg-neutral-900"
        >
            <p class="p-2 text-xs text-gray-500 sm:p-5 sm:text-sm dark:text-neutral-500">
                {{ initialSharedData('appName') }} © {{ __('all_rights_reserved') }}. {{ new Date().getFullYear() }}.
            </p>
        </footer>
    </App>
</template>
