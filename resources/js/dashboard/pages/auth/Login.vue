<script setup lang="ts">
import Button from '@/dashboard/components/button/Button.vue';
import InputError from '@/dashboard/components/inputs/InputError.vue';
import InputLabel from '@/dashboard/components/inputs/InputLabel.vue';
import PasswordInput from '@/dashboard/components/inputs/PasswordInput.vue';
import TextInput from '@/dashboard/components/inputs/TextInput.vue';
import AuthLayout from '@/dashboard/layouts/AuthLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('dashboard.login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <AuthLayout :title="__('authentication.login.name')">
        <template #title>
            {{ __('authentication.login.title', { appName: config('appName') }) }}
        </template>

        <template #description>
            {{
                __('authentication.login.description', {
                    appName: config('appName'),
                })
            }}
        </template>

        <form @submit.prevent="submit">
            <div class="space-y-5">
                <div>
                    <InputLabel
                        for="email"
                        :value="__('global.attributes.email')"
                        class="mb-2 font-medium text-gray-800 dark:text-white"
                    />

                    <TextInput
                        v-model="form.email"
                        :hasError="form.errors.hasOwnProperty('email')"
                        required
                        id="email"
                        type="email"
                        placeholder="example@example.com"
                        autocomplete="email"
                    />

                    <InputError v-if="form.errors.email" :message="form.errors.email" />
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <InputLabel
                            for="password"
                            :value="__('global.attributes.password')"
                            class="mb-2 font-medium text-gray-800 dark:text-white"
                        />

                        <a
                            href="#"
                            class="inline-flex items-center gap-x-1.5 text-xs text-gray-600 decoration-2 hover:text-gray-700 hover:underline focus:underline focus:outline-hidden dark:text-neutral-500 dark:hover:text-neutral-600"
                        >
                            {{ __('authentication.login.forgot_your_password') }}
                        </a>
                    </div>

                    <PasswordInput
                        v-model="form.password"
                        id="password"
                        autocomplete="current-password"
                        required
                        :hasError="form.errors.hasOwnProperty('password')"
                    />

                    <InputError :message="form.errors.password" />
                </div>

                <Button
                    type="submit"
                    class="w-full"
                    :value="__('authentication.login.submit')"
                    :disabled="form.processing"
                />
            </div>
        </form>
    </AuthLayout>
</template>
