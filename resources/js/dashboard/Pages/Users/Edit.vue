<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import ButtonLink from '@/dashboard/Components/Button/ButtonLink.vue';
import DateInput from '@/dashboard/Components/Inputs/DateInput.vue';
import InputError from '@/dashboard/Components/Inputs/InputError.vue';
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
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
        text: __('resources.user.plural'),
        href: route('dashboard.users.index'),
    },
    { text: __('global.crud.edit') },
];

const props = defineProps<{
    user: App.Models.User;
}>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone,
    phone_country: props.user.phone_country,
    birth_date: props.user.birth_date,
    gender: props.user.gender,
});

const submit = () => {
    form.put(route('dashboard.users.update', props.user.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toasts.success(__('global.actions.updated', { resource: __('resources.user.singular') }));
            form.reset();
        },
        onError: () => toasts.danger(__('errors.error_happened')),
    });
};

const genders = computed(() => {
    return Object.values(App.Enums.Gender).map((gender) => {
        return {
            label: __('global.enums.gender.' + gender),
            value: gender,
        } as Option;
    });
});
</script>

<template>
    <AppLayout
        :title="
            __('global.resource.edit', {
                resource: __('resources.user.singular'),
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
                        __('global.resource.edit', {
                            resource: __('resources.user.singular'),
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
                            id="name"
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
                            id="email"
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
                        id="phone"
                    />

                    <InputError :message="form.errors.phone" />
                </div>

                <div class="grid gap-y-1.5 sm:grid-cols-12 sm:gap-x-5 sm:gap-y-0">
                    <div class="col-span-6">
                        <InputLabel :value="__('global.gender')" for="gender" class="mb-2" />

                        <Select
                            :attribute="__('global.gender')"
                            v-model="form.gender"
                            :options="genders"
                            :hasError="form.errors.hasOwnProperty('gender')"
                            id="gender"
                        />

                        <InputError :message="form.errors.gender" />
                    </div>

                    <div class="col-span-6">
                        <InputLabel :value="__('resources.user.attributes.birth_date')" for="birth_date" class="mb-2" />

                        <DateInput
                            v-model="form.birth_date"
                            id="birth_date"
                            :placeholder="
                                __('global.placeholder', {
                                    attribute: __('resources.user.attributes.birth_date'),
                                })
                            "
                            :hasError="form.errors.hasOwnProperty('birth_date')"
                        />

                        <InputError :message="form.errors.birth_date" />
                    </div>
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
                    :href="route('dashboard.users.index')"
                    :value="__('global.closure.cancel')"
                />

                <Button type="submit" :value="__('global.crud.edit')" :disabled="form.processing" />
            </div>
            <!-- End Footer -->
        </form>
        <!-- End Card -->
    </AppLayout>
</template>
