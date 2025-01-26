<script setup lang="ts">
import * as Buttons from '@/dashboard/Components/Buttons/Index';
import InputError from '@/dashboard/Components/Inputs/InputError.vue';
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import PasswordInput from '@/dashboard/Components/Inputs/PasswordInput.vue';
import Modal from '@/dashboard/Components/Modals/Modal.vue';
import useLocalization from '@/dashboard/composables/useLocalization';
import { useForm } from '@inertiajs/vue3';
import { HSOverlay } from 'preline/preline';
import { nextTick, ref } from 'vue';

const { __ } = useLocalization();

const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    HSOverlay.open(document.getElementById('delete-user') as HTMLElement);
    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('dashboard.profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            dashboardApp.success(__('global.actions.deleted', { resource: __('profile.name') }));
            closeModal();
        },
        onError: () => {
            passwordInput.value?.focus();
            dashboardApp.danger(__('errors.error_happened'));
        },
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    HSOverlay.close(document.getElementById('delete-user') as HTMLElement);
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <div class="space-y-5 border-t border-gray-200 py-6 first:border-t-0 dark:border-neutral-700 sm:py-8">
        <!-- Grid -->
        <div class="grid gap-y-1.5 sm:grid-cols-12 sm:gap-x-5 sm:gap-y-0">
            <div class="sm:col-span-4 xl:col-span-3 2xl:col-span-2">
                <div class="mb-4 xl:mb-8">
                    <h1 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                        {{ __('profile.delete_account.title') }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-neutral-500">
                        {{ __('profile.delete_account.description') }}
                    </p>
                </div>
            </div>
            <!-- End Col -->

            <div class="sm:col-span-8 xl:col-span-4">
                <Buttons.White.Danger @click="confirmUserDeletion" :value="__('profile.delete_account.button.text')" />

                <p class="mt-3 text-sm text-gray-500 dark:text-neutral-500">
                    {{ __('profile.delete_account.button.description') }}
                </p>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Grid -->
    </div>

    <Modal size="2xl" name="delete-user" @closed="closeModal">
        <!-- Header -->
        <div class="flex items-center justify-between border-b px-4 py-2.5 dark:border-neutral-700">
            <h3 id="delete-user-label" class="font-medium text-gray-800 dark:text-neutral-200">
                {{ __('profile.delete_account.model.title') }}
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
                        <p class="font-medium text-gray-800 dark:text-neutral-200">
                            {{ __('profile.delete_account.model.tooltip.title') }}
                        </p>
                        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-neutral-400">
                            {{ __('profile.delete_account.model.tooltip.description') }}
                        </p>
                    </div>
                </div>
                <!-- End Tooltip -->
            </h3>
            <button
                type="button"
                class="inline-flex size-6 items-center justify-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:bg-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-600 dark:focus:bg-neutral-600"
                aria-label="Close"
                data-hs-overlay="#delete-user"
            >
                <span class="sr-only">{{ __('global.closure.close') }}</span>
                <svg
                    class="size-4 shrink-0"
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>
        <!-- End Header -->

        <!-- Body -->
        <div>
            <div class="space-y-5 p-4">
                <div>
                    <InputLabel
                        :value="__('global.attributes.current_password')"
                        for="password_confirmation_for_delete"
                        class="mb-2"
                    />

                    <PasswordInput
                        v-model="form.password"
                        :hasError="form.errors.hasOwnProperty('password')"
                        required
                        id="password_confirmation_for_delete"
                        autocomplete="current-password"
                    />

                    <InputError :message="form.errors.password" />
                </div>
            </div>
        </div>
        <!-- End Body -->

        <!-- Footer -->
        <div class="flex justify-end gap-x-2 p-4">
            <div class="flex flex-1 flex-wrap items-center justify-end gap-2">
                <Buttons.White.White type="button" @click="closeModal()" :value="__('global.closure.cancel')" />

                <Buttons.Solid.Danger
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteUser"
                    :value="__('global.crud.delete')"
                />
            </div>
        </div>
        <!-- End Footer -->
    </Modal>
</template>
