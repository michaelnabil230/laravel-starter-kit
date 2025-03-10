<script setup lang="ts">
import useLocalization from '@/dashboard/composables/useLocalization';
import AppLayout from '@/dashboard/layouts/AppLayout.vue';

const { __ } = useLocalization();

defineProps<{
    usersCount: number;
}>();

const breadcrumbs: Breadcrumbs = [{ text: __('dashboard') }];
</script>

<template>
    <AppLayout :title="__('home')" :breadcrumbs="breadcrumbs">
        <!-- Stats Grid -->
        <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4 xl:gap-4">
            <!-- Card -->
            <div
                class="flex flex-col rounded-xl border border-gray-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-800"
            >
                <h2 class="mb-1 text-4xl font-semibold text-gray-800 dark:text-neutral-200">
                    {{ usersCount }}
                </h2>

                <h3 class="text-gray-500 dark:text-neutral-500">
                    {{ __('resources.user.plural') }}
                </h3>
            </div>
            <!-- End Card -->
        </div>
        <!-- End Stats Grid -->
    </AppLayout>
</template>
