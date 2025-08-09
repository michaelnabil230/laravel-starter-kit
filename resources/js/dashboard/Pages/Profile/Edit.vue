<script setup lang="ts">
import useLocalization from '@/dashboard/composables/useLocalization';
import AppLayout from '@/dashboard/Layouts/AppLayout.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

const { __ } = useLocalization();

const breadcrumbs: Breadcrumbs = [
    { text: __('dashboard'), href: route('dashboard.welcome') },
    { text: __('profile.name') },
];
</script>

<template>
    <AppLayout :title="__('profile.name')" :breadcrumbs="breadcrumbs">
        <!-- Personal Info -->
        <UpdateProfileInformationForm />
        <!-- End Personal Info -->

        <!-- Password -->
        <UpdatePasswordForm />
        <!-- End Password -->
    </AppLayout>
</template>
