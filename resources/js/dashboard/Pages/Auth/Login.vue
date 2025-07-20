<script setup lang="ts">
import LoginLink from '@/../../vendor/spatie/laravel-login-link/resources/js/login-link.vue';
import Button from '@/dashboard/Components/Button/Button.vue';
import { buttonVariants } from '@/dashboard/Components/Button/Index';
import InputError from '@/dashboard/Components/Inputs/InputError.vue';
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import PasswordInput from '@/dashboard/Components/Inputs/PasswordInput.vue';
import TextInput from '@/dashboard/Components/Inputs/TextInput.vue';
import AuthLayout from '@/dashboard/Layouts/AuthLayout.vue';
import { cn } from '@/dashboard/lib/utils';
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
            {{ __('authentication.login.title', { appName: initialSharedData('appName') }) }}
        </template>

        <template #description>
            {{
                __('authentication.login.description', {
                    appName: initialSharedData('appName'),
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

                    <InputError :message="form.errors.email" />
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

                <LoginLink
                    v-if="initialSharedData('environment') === 'local'"
                    label="Login as Super Admin"
                    :class="cn(buttonVariants({ variant: 'contained', size: 'small', color: 'danger' }), 'w-full')"
                    redirectUrl="/dashboard"
                    email="super-admin@app.com"
                />
            </div>
        </form>
    </AuthLayout>
</template>
