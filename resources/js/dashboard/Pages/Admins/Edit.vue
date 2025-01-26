<script setup lang="ts">
import * as Buttons from '@/dashboard/Components/Buttons/Index';
import InputError from '@/dashboard/Components/Inputs/InputError.vue';
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import PhoneInput from '@/dashboard/Components/Inputs/PhoneInput.vue';
import SelectInput from '@/dashboard/Components/Inputs/SelectInput.vue';
import TextInput from '@/dashboard/Components/Inputs/TextInput.vue';
import * as Links from '@/dashboard/Components/Links/Index';
import useLocalization from '@/dashboard/composables/useLocalization';
import AppLayout from '@/dashboard/Layouts/AppLayout.vue';
import { Role } from '@/dashboard/types/enums/admin';
import { App } from '@/dashboard/types/models';
import { Select } from '@/dashboard/types/select';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const { __ } = useLocalization();

const breadcrumbs: Breadcrumbs = [
    { text: __('dashboard'), href: route('dashboard.welcome') },
    {
        text: __('resources.admin.plural'),
        href: route('dashboard.admins.index'),
    },
    { text: __('global.crud.edit') },
];

const props = defineProps<{
    admin: App.Models.Admin;
}>();

const form = useForm({
    name: props.admin.name,
    email: props.admin.email,
    phone: props.admin.phone,
    phone_country: '',
    role: props.admin.role,
});

const submit = () => {
    form.put(route('dashboard.admins.update', props.admin.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            dashboardApp.success(__('global.actions.updated', { resource: __('resources.admin.singular') }));
            form.reset();
        },
        onError: () => dashboardApp.danger(__('errors.error_happened')),
    });
};

const roles = computed(() => {
    return Object.values(Role).map((role) => {
        return {
            label: __('resources.admin.enums.role.' + role),
            value: role,
        } as Select;
    });
});
</script>

<template>
    <AppLayout
        :title="
            __('global.resource.edit', {
                resource: __('resources.admin.singular'),
            })
        "
        :breadcrumbs="breadcrumbs"
    >
        <!-- Card -->
        <form
            @submit.prevent="submit()"
            class="flex flex-col rounded-xl border border-stone-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800"
        >
            <!-- Header -->
            <div
                class="flex items-center justify-between gap-x-5 border-b border-stone-200 px-5 py-3 dark:border-neutral-700"
            >
                <h2 class="inline-block font-semibold text-stone-800 dark:text-neutral-200">
                    {{
                        __('global.resource.edit', {
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

                <div>
                    <InputLabel :value="__('resources.admin.attributes.role')" for="role" class="mb-2" />

                    <SelectInput
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
                <Links.White.White :href="route('dashboard.admins.index')" :value="__('global.closure.cancel')" />

                <Buttons.Solid.Primary
                    type="submit"
                    :value="__('global.crud.edit')"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                />
            </div>
            <!-- End Footer -->
        </form>
        <!-- End Card -->
    </AppLayout>
</template>
