<script setup lang="ts">
import Breadcrumbs from '@/dashboard/Components/Breadcrumbs.vue';
import Header from '@/dashboard/Components/Header.vue';
import Modal from '@/dashboard/Components/Modal';
import Search from '@/dashboard/Components/Search.vue';
import Sidebar from '@/dashboard/Components/Sidebar/Index.vue';
import Toasts from '@/dashboard/Components/Toasts/Index.vue';
import { Head } from '@inertiajs/vue3';
import { HSOverlay, ICollectionItem } from 'preline/preline';
import { onBeforeUnmount, onMounted } from 'vue';

defineProps<{
    title?: string;
    breadcrumbs?: Breadcrumbs;
}>();

onMounted(() => dashboardApp.preline());

onBeforeUnmount(() => {
    // @ts-ignore
    const hsOverlay = HSOverlay.getInstance('#sidebar', true) as ICollectionItem<HSOverlay>;
    hsOverlay.element.close(true);
    hsOverlay.element.destroy();
});
</script>

<template>
    <Head :title="title" />

    <Header />

    <Sidebar />

    <Search />

    <Toasts />

    <Modal />

    <main class="h-full min-h-screen pt-[59px] lg:ms-[260px]">
        <div class="p-2 space-y-5 sm:p-5 sm:py-0 md:pt-5">
            <Breadcrumbs v-if="breadcrumbs != null" :breadcrumbs="breadcrumbs" />
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
</template>
