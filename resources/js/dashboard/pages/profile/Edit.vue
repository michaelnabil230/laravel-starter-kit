<script setup lang="ts">
import useLocalization from '@/dashboard/composables/useLocalization';
import AppLayout from '@/dashboard/layouts/AppLayout.vue';
import DeleteUserForm from './partials/DeleteUserForm.vue';
import UpdatePasswordForm from './partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './partials/UpdateProfileInformationForm.vue';

const { __ } = useLocalization();

const breadcrumbs: Breadcrumbs = [
    { text: __('dashboard'), href: route('dashboard.welcome') },
    { text: __('profile.name') },
];
</script>

<template>
    <AppLayout :title="__('profile.name')" :breadcrumbs="breadcrumbs">
        <div class="pt-2">
            <div
                class="rounded-xl border border-gray-200 bg-white p-5 shadow-2xs dark:border-neutral-700 dark:bg-neutral-800 md:p-8"
            >
                <div class="mb-4 xl:mb-8">
                    <h1 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                        {{ __('profile.name') }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-neutral-500">
                        {{ __('profile.description') }}
                    </p>
                </div>

                <!-- Personal Info -->
                <UpdateProfileInformationForm />
                <!-- End Personal Info -->

                <!-- Password -->
                <UpdatePasswordForm />
                <!-- End Password -->

                <!-- Danger Zone -->
                <DeleteUserForm />
                <!-- End Danger Zone -->
            </div>
        </div>
    </AppLayout>
</template>
