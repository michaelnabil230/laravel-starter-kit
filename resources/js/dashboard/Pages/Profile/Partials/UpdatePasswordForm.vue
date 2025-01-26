<script setup lang="ts">
import * as Buttons from '@/dashboard/Components/Buttons/Index';
import InputError from '@/dashboard/Components/Inputs/InputError.vue';
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import PasswordInput from '@/dashboard/Components/Inputs/PasswordInput.vue';
import useLocalization from '@/dashboard/composables/useLocalization';
import { useForm } from '@inertiajs/vue3';

const { __ } = useLocalization();

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.put(route('dashboard.password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            dashboardApp.success(__('global.actions.updated', { resource: __('profile.password.title') }));
            form.reset();
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
            }

            if (form.errors.current_password) {
                form.reset('current_password');
            }

            dashboardApp.danger(__('errors.error_happened'));
        },
    });
};
</script>

<template>
    <div class="border-t border-gray-200 py-6 first:border-t-0 dark:border-neutral-700 sm:py-8">
        <div class="inline-flex items-center gap-x-2">
            <h2 class="font-semibold text-gray-800 dark:text-neutral-200">
                {{ __('profile.password.title') }}
            </h2>

            <!-- Tooltip -->
            <div class="hs-tooltip inline-block">
                <svg
                    class="hs-tooltip-toggle ms-1 size-3 flex-shrink-0 text-gray-500 dark:text-neutral-500"
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    viewBox="0 0 16 16"
                >
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                    <path
                        d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"
                    />
                </svg>
                <div
                    class="hs-tooltip-content invisible absolute z-[60] inline-block w-96 rounded-xl bg-white p-4 opacity-0 shadow-xl transition-opacity hs-tooltip-shown:visible hs-tooltip-shown:opacity-100 dark:bg-neutral-900 dark:text-neutral-400"
                    role="tooltip"
                >
                    <p class="font-medium text-gray-800 dark:text-neutral-200">Password requirements:</p>
                    <p class="mt-1 text-sm font-normal text-gray-500 dark:text-neutral-400">
                        Ensure that these requirements are met:
                    </p>
                    <ul
                        class="mt-1 list-outside list-disc ps-3.5 text-sm font-normal text-gray-500 dark:text-neutral-400"
                    >
                        <li>Minimum 8 characters long - the more, the better</li>
                        <li>At least one lowercase character</li>
                        <li>At least one uppercase character</li>
                        <li>At least one number, symbol, or whitespace character</li>
                    </ul>
                </div>
            </div>
            <!-- End Tooltip -->
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
                    <div data-hs-toggle-password-group class="space-y-2">
                        <!-- Input -->
                        <PasswordInput
                            v-model="form.password"
                            :hasError="form.errors.hasOwnProperty('password')"
                            required
                            id="password"
                            :placeholder="__('profile.password.placeholder.new_password')"
                            autocomplete="new-password"
                            :ids="['#password_confirmation']"
                        />
                        <InputError :message="form.errors.password" />
                        <!-- End Input -->

                        <!-- Input -->
                        <PasswordInput
                            v-model="form.password_confirmation"
                            :hasError="form.errors.hasOwnProperty('password_confirmation')"
                            required
                            id="password_confirmation"
                            :placeholder="__('profile.password.placeholder.new_password_confirmation')"
                            autocomplete="new-password"
                            :ids="['#password']"
                        />
                        <InputError :message="form.errors.password_confirmation" />
                        <!-- End Input -->

                        <div
                            data-hs-strong-password='{
                            "target": "#password",
                            "stripClasses": "hs-strong-password:opacity-100 hs-strong-password-accepted:bg-blue-500 h-2 flex-auto rounded-full bg-blue-500 opacity-50 mx-1"
                        }'
                            class="-mx-1 mt-2 flex"
                        ></div>

                        <p class="text-sm text-gray-600 dark:text-neutral-400">Level: <span></span></p>

                        <!-- Button Group -->
                        <div class="flex items-center gap-x-3">
                            <Buttons.Solid.Primary
                                type="submit"
                                :disabled="form.processing"
                                :class="{ 'opacity-25': form.processing }"
                                :value="__('profile.password.button')"
                            />
                            <a
                                href="#"
                                class="text-sm font-medium text-blue-700 decoration-2 hover:underline focus:underline focus:outline-none dark:text-blue-400 dark:hover:text-blue-500"
                            >
                                I forgot my password
                            </a>
                        </div>
                        <!-- End Button Group -->
                    </div>
                </div>
                <!-- End Col -->
            </div>
            <!-- End Grid -->
        </form>
    </div>
</template>
