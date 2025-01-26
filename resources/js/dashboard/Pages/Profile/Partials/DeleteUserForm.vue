<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import InputError from '@/dashboard/Components/Inputs/InputError.vue';
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import PasswordInput from '@/dashboard/Components/Inputs/PasswordInput.vue';
import Modal from '@/dashboard/Components/Modal/Modal.vue';
import ModalClose from '@/dashboard/Components/Modal/ModalClose.vue';
import ModalDescription from '@/dashboard/Components/Modal/ModalDescription.vue';
import ModalFooter from '@/dashboard/Components/Modal/ModalFooter.vue';
import ModalTitle from '@/dashboard/Components/Modal/ModalTitle.vue';
import useLocalization from '@/dashboard/composables/useLocalization';
import useToasts from '@/dashboard/composables/useToasts';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { __ } = useLocalization();
const toasts = useToasts();

const form = useForm({
    password: '',
});

const open = ref(false);

const openModal = () => (open.value = true);

const deleteUser = () => {
    form.delete(route('dashboard.profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            toasts.success(__('global.actions.deleted', { resource: __('profile.name') }));
            closeModal();
        },
        onError: () => toasts.danger(__('errors.error_happened')),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    open.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <div class="space-y-5 border-t border-gray-200 py-6 first:border-t-0 sm:py-8 dark:border-neutral-700">
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
                <Button
                    variant="text"
                    color="danger"
                    @click="openModal"
                    :value="__('profile.delete_account.button.text')"
                />

                <p class="mt-3 text-sm text-gray-500 dark:text-neutral-500">
                    {{ __('profile.delete_account.button.description') }}
                </p>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Grid -->
    </div>

    <Modal v-model="open" @closed="closeModal">
        <!-- Header -->
        <div class="border-b border-gray-200 px-4 py-2.5 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <ModalTitle>
                    {{ __('profile.delete_account.model.title') }}
                </ModalTitle>

                <ModalClose />
            </div>

            <ModalDescription class="mt-2">
                {{ __('profile.delete_account.model.description') }}
            </ModalDescription>
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
        <ModalFooter>
            <Button
                color="secondary"
                variant="outlined"
                type="button"
                @click="closeModal()"
                :value="__('global.closure.cancel')"
            />

            <Button color="danger" @click="deleteUser" :disabled="form.processing" :value="__('global.crud.delete')" />
        </ModalFooter>
        <!-- End Footer -->
    </Modal>
</template>
