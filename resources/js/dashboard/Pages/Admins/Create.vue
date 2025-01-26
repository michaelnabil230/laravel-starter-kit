<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import ButtonLink from '@/dashboard/Components/Button/ButtonLink.vue';
import InputError from '@/dashboard/Components/Inputs/InputError.vue';
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import PasswordInput from '@/dashboard/Components/Inputs/PasswordInput.vue';
import PhoneInput from '@/dashboard/Components/Inputs/PhoneInput.vue';
import Select from '@/dashboard/Components/Inputs/Select/Select.vue';
import TextInput from '@/dashboard/Components/Inputs/TextInput.vue';
import useLocalization from '@/dashboard/composables/useLocalization';
import useToasts from '@/dashboard/composables/useToasts';
import AppLayout from '@/dashboard/Layouts/AppLayout.vue';
import { App } from '@/dashboard/types/app';
import { Option } from '@/dashboard/types/option';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const { __ } = useLocalization();
const toasts = useToasts();

const breadcrumbs: Breadcrumbs = [
    { text: __('dashboard'), href: route('dashboard.welcome') },
    {
        text: __('resources.admin.plural'),
        href: route('dashboard.admins.index'),
    },
    { text: __('global.crud.create') },
];

const form = useForm({
    name: '',
    email: '',
    phone: '',
    phone_country: '',
    password: '',
    password_confirmation: '',
    role: '',
});

const submit = () => {
    form.post(route('dashboard.admins.store'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toasts.success(__('global.actions.created', { resource: __('resources.admin.singular') }));
            form.reset();
        },
        onError: () => toasts.danger(__('errors.error_happened')),
    });
};

const roles = computed(() => {
    return Object.values(App.Enums.Role).map((role) => {
        return {
            label: __('resources.admin.enums.role.' + role),
            value: role,
        } as Option;
    });
});
</script>

<template>
    <AppLayout
        :title="
            __('global.resource.create', {
                resource: __('resources.admin.singular'),
            })
        "
        :breadcrumbs="breadcrumbs"
    >
        <!-- Card -->
        <form
            @submit.prevent="submit()"
            class="flex flex-col rounded-xl border border-stone-200 bg-white shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
        >
            <!-- Header -->
            <div
                class="flex items-center justify-between gap-x-5 border-b border-stone-200 px-5 py-3 dark:border-neutral-700"
            >
                <h2 class="inline-block font-semibold text-stone-800 dark:text-neutral-200">
                    {{
                        __('global.resource.create', {
                            resource: __('resources.admin.singular'),
                        })
                    }}
                </h2>
            </div>
            <!-- End Header -->

            <!-- Body -->
            <div class="space-y-4 p-5">
                <div class="grid gap-y-1.5 sm:grid-cols-12 sm:gap-x-5 sm:gap-y-0">
                    <div class="col-span-6">
                        <InputLabel :value="__('global.attributes.name')" for="name" class="mb-2" />

                        <TextInput
                            v-model="form.name"
                            type="text"
                            :placeholder="
                                __('global.placeholder', {
                                    attribute: __('global.attributes.name'),
                                })
                            "
                            :hasError="form.errors.hasOwnProperty('name')"
                        />

                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="col-span-6">
                        <InputLabel :value="__('global.attributes.email')" for="email" class="mb-2" />

                        <TextInput
                            v-model="form.email"
                            type="email"
                            :placeholder="
                                __('global.placeholder', {
                                    attribute: __('global.attributes.email'),
                                })
                            "
                            :hasError="form.errors.hasOwnProperty('email')"
                        />

                        <InputError :message="form.errors.email" />
                    </div>
                </div>

                <div>
                    <InputLabel :value="__('global.attributes.phone')" for="phone" class="mb-2" />

                    <PhoneInput
                        v-model:phone="form.phone"
                        v-model:phone_country="form.phone_country"
                        :placeholder="
                            __('global.placeholder', {
                                attribute: __('global.attributes.phone'),
                            })
                        "
                        :hasError="form.errors.hasOwnProperty('phone')"
                    />

                    <InputError :message="form.errors.phone" />
                </div>

                <div class="grid gap-y-1.5 sm:grid-cols-12 sm:gap-x-5 sm:gap-y-0">
                    <div class="col-span-6">
                        <InputLabel :value="__('global.attributes.password')" for="password" class="mb-2" />

                        <PasswordInput
                            v-model="form.password"
                            id="password"
                            :placeholder="
                                __('global.placeholder', {
                                    attribute: __('global.attributes.password'),
                                })
                            "
                            :hasError="form.errors.hasOwnProperty('password')"
                        />

                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="col-span-6">
                        <InputLabel
                            :value="__('global.attributes.password_confirmation')"
                            for="password_confirmation"
                            class="mb-2"
                        />

                        <PasswordInput
                            v-model="form.password_confirmation"
                            id="password_confirmation"
                            :placeholder="
                                __('global.placeholder', {
                                    attribute: __('global.attributes.password_confirmation'),
                                })
                            "
                            :hasError="form.errors.hasOwnProperty('password_confirmation')"
                        />

                        <InputError :message="form.errors.password_confirmation" />
                    </div>
                </div>

                <div>
                    <InputLabel :value="__('resources.admin.attributes.role')" for="role" class="mb-2" />

                    <Select
                        :attribute="__('resources.admin.attributes.role')"
                        v-model="form.role"
                        :options="roles"
                        :hasError="form.errors.hasOwnProperty('role')"
                        id="role"
                    />

                    <InputError :message="form.errors.role" />
                </div>
            </div>
            <!-- End Body -->

            <!-- Footer -->
            <div
                class="flex items-center justify-end gap-x-2.5 border-t border-stone-200 px-5 py-3 dark:border-neutral-700"
            >
                <ButtonLink
                    color="secondary"
                    variant="outlined"
                    :href="route('dashboard.admins.index')"
                    :value="__('global.closure.cancel')"
                />

                <Button type="submit" :value="__('global.crud.create')" :disabled="form.processing" />
            </div>
            <!-- End Footer -->
        </form>
        <!-- End Card -->
    </AppLayout>
</template>
