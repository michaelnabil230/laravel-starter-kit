<script setup lang="ts">
import Breadcrumbs from '@/dashboard/components/Breadcrumbs.vue';
import Header from '@/dashboard/components/Header.vue';
import Sidebar from '@/dashboard/components/Sidebar.vue';
import Toasts from '@/dashboard/components/toasts/Index.vue';
import useModal from '@/dashboard/composables/useModal';
import { Head } from '@inertiajs/vue3';
import { defineComponent } from 'vue';
import App from './App.vue';

defineProps<{
    title?: string;
    breadcrumbs?: Breadcrumbs;
}>();

const Modal = defineComponent({
    setup() {
        const { vnode } = useModal();

        return () => vnode.value;
    },
});
</script>

<template>
    <App>
        <Head :title="title" />

        <Header />

        <Sidebar />

        <Toasts />

        <Modal />

        <main class="h-full min-h-screen pt-[59px] lg:ms-[260px]">
            <div class="p-2 space-y-5 sm:p-5 sm:py-0 md:pt-5">
                <Breadcrumbs v-if="breadcrumbs" :breadcrumbs="breadcrumbs" />
                <slot />
            </div>
        </main>

        <footer
            class="relative inset-x-0 bottom-0 mt-5 h-10 border-t border-gray-200 bg-white dark:border-neutral-700 dark:bg-neutral-900 sm:h-16 lg:ms-[260px]"
        >
            <p class="p-2 text-xs text-gray-500 dark:text-neutral-500 sm:p-5 sm:text-sm">
                {{ config('appName') }} © {{ __('all_rights_reserved') }}. {{ new Date().getFullYear() }}.
            </p>
        </footer>
    </App>
</template>
