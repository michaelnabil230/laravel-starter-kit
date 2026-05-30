<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import InputError from '@/dashboard/Components/Inputs/InputError.vue';
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import TextInput from '@/dashboard/Components/Inputs/TextInput.vue';
import { useAuth } from '@/dashboard/composables/useAuth';
import useLocalization from '@/dashboard/composables/useLocalization';
import useToasts from '@/dashboard/composables/useToasts';
import { useForm } from '@inertiajs/vue3';

const { __ } = useLocalization();
const toasts = useToasts();

const { auth } = useAuth();

const form = useForm({
    name: auth.value.admin.name,
    email: auth.value.admin.email,
});

const submit = (): void => {
    form.put(route('dashboard.profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            toasts.success(__('global.actions.updated', { resource: __('profile.name') }));
        },
        onError: () => toasts.danger(__('errors.error_happened')),
    });
};
</script>

<template>
    <form
        @submit.prevent="submit()"
        class="flex flex-col rounded-xl border border-stone-200 bg-white shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
    >
        <div class="border-b border-stone-200 px-5 py-3 dark:border-neutral-700">
            <h2 class="inline-block font-semibold text-stone-800 dark:text-neutral-200">
                {{ __('profile.personal_info.title') }}
            </h2>
        </div>

        <div class="space-y-4 p-5">
            <div class="grid gap-y-1.5 sm:grid-cols-12 sm:gap-x-5 sm:gap-y-0">
                <div class="sm:col-span-4 xl:col-span-3 2xl:col-span-2">
                    <InputLabel for="name" value="Name" />
                </div>

                <div class="sm:col-span-8 xl:col-span-4">
                    <TextInput
                        v-model="form.name"
                        :hasError="form.errors.hasOwnProperty('name')"
                        id="name"
                        type="text"
                        :placeholder="__('profile.personal_info.placeholder.name')"
                        required
                        autofocus
                        autocomplete="name"
                    />

                    <InputError :message="form.errors.name" />
                </div>
            </div>

            <div class="grid gap-y-1.5 sm:grid-cols-12 sm:gap-x-5 sm:gap-y-0">
                <div class="sm:col-span-4 xl:col-span-3 2xl:col-span-2">
                    <InputLabel for="email" value="Email" />
                </div>

                <div class="sm:col-span-8 xl:col-span-4">
                    <TextInput
                        v-model="form.email"
                        :hasError="form.errors.hasOwnProperty('email')"
                        id="email"
                        type="email"
                        :placeholder="__('profile.personal_info.placeholder.email')"
                        required
                        autocomplete="username"
                    />

                    <InputError :message="form.errors.email" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end border-t border-stone-200 px-5 py-3 dark:border-neutral-700">
            <Button type="submit" :value="__('global.crud.save')" :disabled="form.processing" />
        </div>
    </form>
</template>
