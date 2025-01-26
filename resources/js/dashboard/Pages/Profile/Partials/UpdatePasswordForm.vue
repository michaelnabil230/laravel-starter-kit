<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import InputError from '@/dashboard/Components/Inputs/InputError.vue';
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import PasswordInput from '@/dashboard/Components/Inputs/PasswordInput.vue';
import useLocalization from '@/dashboard/composables/useLocalization';
import useToasts from '@/dashboard/composables/useToasts';
import { useForm } from '@inertiajs/vue3';

const { __ } = useLocalization();
const toasts = useToasts();

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.put(route('dashboard.password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            toasts.success(__('global.actions.updated', { resource: __('profile.password.title') }));
            form.reset();
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
            }

            if (form.errors.current_password) {
                form.reset('current_password');
            }

            toasts.danger(__('errors.error_happened'));
        },
    });
};
</script>

<template>
    <div class="border-t border-gray-200 py-6 first:border-t-0 sm:py-8 dark:border-neutral-700">
        <div class="inline-flex items-center gap-x-2">
            <h2 class="font-semibold text-gray-800 dark:text-neutral-200">
                {{ __('profile.password.title') }}
            </h2>
        </div>

        <form @submit.prevent="submit()" class="space-y-5">
            <slot />

            <!-- Grid -->
            <div class="grid gap-y-1.5 sm:grid-cols-12 sm:gap-x-5 sm:gap-y-0">
                <div class="sm:col-span-4 xl:col-span-3 2xl:col-span-2">
                    <InputLabel
                        for="current-password"
                        :value="__('global.attributes.current_password')"
                        class="sm:mt-2.5"
                    />
                </div>
                <!-- End Col -->

                <div class="sm:col-span-8 xl:col-span-4">
                    <!-- Input -->
                    <PasswordInput
                        v-model="form.current_password"
                        :hasError="form.errors.hasOwnProperty('current_password')"
                        required
                        id="current-password"
                        autocomplete="current-password"
                        :placeholder="__('profile.password.placeholder.current_password')"
                    />
                    <InputError :message="form.errors.current_password" />
                    <!-- End Input -->
                </div>
                <!-- End Col -->
            </div>
            <!-- End Grid -->

            <!-- Grid -->
            <div class="grid gap-y-1.5 sm:grid-cols-12 sm:gap-x-5 sm:gap-y-0">
                <div class="sm:col-span-4 xl:col-span-3 2xl:col-span-2">
                    <InputLabel for="password" :value="__('global.attributes.new_password')" class="sm:mt-2.5" />

                    <InputLabel
                        for="password_confirmation"
                        :value="__('global.attributes.new_password_confirmation')"
                        class="sr-only"
                    />
                </div>
                <!-- End Col -->

                <div class="sm:col-span-8 xl:col-span-4">
                    <div class="space-y-2">
                        <!-- Input -->
                        <div>
                            <PasswordInput
                                v-model="form.password"
                                :hasError="form.errors.hasOwnProperty('password')"
                                required
                                id="password"
                                :placeholder="__('profile.password.placeholder.new_password')"
                                autocomplete="new-password"
                            />
                            <InputError :message="form.errors.password" />
                        </div>
                        <!-- End Input -->

                        <!-- Input -->
                        <div>
                            <PasswordInput
                                v-model="form.password_confirmation"
                                :hasError="form.errors.hasOwnProperty('password_confirmation')"
                                required
                                id="password_confirmation"
                                :placeholder="__('profile.password.placeholder.new_password_confirmation')"
                                autocomplete="new-password"
                            />
                            <InputError :message="form.errors.password_confirmation" />
                        </div>
                        <!-- End Input -->
                    </div>
                </div>
                <!-- End Col -->
            </div>

            <div class="grid gap-y-1.5 sm:grid-cols-12 sm:gap-x-5 sm:gap-y-0">
                <div class="sm:col-span-4 xl:col-span-3 2xl:col-span-2"></div>

                <div class="sm:col-span-8 xl:col-span-4">
                    <!-- Button Group -->
                    <div class="flex items-center gap-x-3">
                        <Button type="submit" :disabled="form.processing" :value="__('profile.password.button')" />

                        <a
                            href="#"
                            class="text-sm font-medium text-blue-700 decoration-2 hover:underline focus:underline focus:outline-hidden dark:text-blue-400 dark:hover:text-blue-500"
                        >
                            I forgot my password
                        </a>
                    </div>
                    <!-- End Button Group -->
                </div>
            </div>
            <!-- End Grid -->
        </form>
    </div>
</template>
