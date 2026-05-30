<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
import { visitModal } from '@/dashboard/composables/modal/modalStack';
import useLocalization from '@/dashboard/composables/useLocalization';
import AppLayout from '@/dashboard/Layouts/AppLayout.vue';
import { App } from '@/dashboard/types/app';
import PhoneVerification from './Partials/PhoneVerification.vue';

const { __ } = useLocalization();

const breadcrumbs: Breadcrumbs = [
    { text: __('dashboard'), href: route('dashboard.welcome') },
    { text: __('resources.user.plural'), href: route('dashboard.users.index') },
    { text: __('global.crud.show') },
];

defineProps<{
    user: App.Models.User;
}>();
</script>

<template>
    <AppLayout
        :title="
            __('global.resource.show', {
                resource: __('resources.user.singular'),
            })
        "
        :breadcrumbs="breadcrumbs"
    >
        <div
            class="rounded-xl border border-stone-200 bg-white px-5 py-3 shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
        >
            <div class="flex w-full flex-col gap-1.5">
                <div class="flex justify-between">
                    <div class="flex items-center gap-2">
                        <span
                            v-if="user.is_phone_verified"
                            class="flex size-4 shrink-0 items-center justify-center rounded-full bg-blue-500"
                        >
                            <svg
                                class="size-3 shrink-0"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="white"
                                stroke-width="3"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M20 6 9 17l-5-5"></path>
                            </svg>
                        </span>
                        <h1 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                            {{ user.name }}
                        </h1>
                    </div>

                    <Button
                        v-if="!user.is_phone_verified"
                        @click="visitModal('#phone-verification')"
                        :value="__('resources.user.phone_verification.title')"
                    />
                </div>

                <div class="grid gap-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <a
                        :href="'tel:' + user.phone"
                        class="inline-flex items-center gap-x-2 text-sm text-gray-500 decoration-2 hover:underline focus:underline dark:text-neutral-500"
                    >
                        <SvgIcon name="icons/phone" class="size-4 shrink-0" />
                        <span dir="ltr">{{ user.phone }}</span>
                    </a>

                    <p class="inline-flex items-center gap-x-2 text-sm text-gray-500 dark:text-neutral-500">
                        <SvgIcon name="icons/birthdate" class="size-4 shrink-0" />
                        {{ user.birth_date ?? 'N/A' }}
                    </p>

                    <p class="inline-flex items-center gap-x-2 text-sm text-gray-500 dark:text-neutral-500">
                        <SvgIcon
                            v-if="user.gender == App.Enums.Gender.MALE"
                            name="icons/gender-male"
                            class="size-4 shrink-0"
                        />
                        <SvgIcon
                            v-if="user.gender == App.Enums.Gender.FEMALE"
                            name="icons/gender-female"
                            class="size-4 shrink-0"
                        />

                        {{ __('global.enums.gender.' + user.gender) }}
                    </p>

                    <p class="inline-flex items-center gap-x-2 text-sm text-gray-500 dark:text-neutral-500">
                        <SvgIcon name="icons/national-id" class="size-4 shrink-0" />
                        {{ user.national_id ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        <PhoneVerification :user />
    </AppLayout>
</template>
