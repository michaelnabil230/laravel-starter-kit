<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import TextInput from '@/dashboard/Components/Inputs/TextInput.vue';
import { Modal, ModalDescription, ModalFooter, ModalHeader, ModalTitle } from '@/dashboard/Components/Modal/Index';
import useLocalization from '@/dashboard/composables/useLocalization';
import translateNumericFormat from '@/dashboard/lib/normalize-number';
import toasts from '@/dashboard/Stores/toasts';
import { App } from '@/dashboard/types/app';
import { router, useHttp } from '@inertiajs/vue3';
import { ComponentPublicInstance, ref } from 'vue';

const { __ } = useLocalization();

const props = defineProps<{
    user: App.Models.User;
}>();

type OtpInputInstance = ComponentPublicInstance & { $el: HTMLElement };

const otpInputs = ref<(OtpInputInstance | null)[]>([]);
const otpCode = ref<string[]>(['', '', '', '']);
const verificationHttp = useHttp<{ otp_code: string }, { message?: string }>({
    otp_code: '',
});
const sendHttp = useHttp<Record<string, never>, { message?: string }>();

const onClose = () => {
    otpCode.value = ['', '', '', ''];
};

const handleOtpInput = (event: Event, index: number) => {
    const input = event.target as HTMLInputElement;

    const value = translateNumericFormat(input.value).replace(/[^0-9]/g, '');

    otpCode.value[index] = value;

    if (value && index < 3) {
        focus(otpInputs.value[index + 1]);
    }
};

const handleOtpKeydown = (event: KeyboardEvent, index: number) => {
    if (event.key === 'Backspace' && !otpCode.value[index] && index > 0) {
        focus(otpInputs.value[index - 1]);
    }
};

const focus = (input: OtpInputInstance | null) => {
    const el = input?.$el ?? input;
    (el?.tagName === 'INPUT' ? el : el?.querySelector('input'))?.focus();
};

const onOpen = () => {
    setTimeout(() => focus(otpInputs.value[0]), 100);
};

const verify = () => {
    verificationHttp.otp_code = otpCode.value.join('');

    verificationHttp.post(route('api.dashboard.users.verification.verify', props.user.id), {
        onSuccess: (data, response) => {
            if (response.status === 204) {
                toasts.success(__('global.actions.updated', { resource: __('resources.user.singular') }));
                router.reload();
                onClose();
            }
        },
        onError: (errors) => {
            toasts.danger(String(errors.otp_code ?? __('errors.error_happened')));
        },
    });
};

const send = () => {
    sendHttp.post(route('api.dashboard.users.verification.send', props.user.id), {
        onSuccess: (data, response) => {
            if (response.status === 204) {
                toasts.success(
                    __('global.actions.created', {
                        resource: __('resources.user.attributes.otp_code'),
                    }),
                );
            } else if (data?.message) {
                toasts.success(data.message);
            }
        },
        onError: () => {
            toasts.danger(__('errors.error_happened'));
        },
    });
};
</script>

<template>
    <Modal name="phone-verification" v-slot="slotProps" @open="onOpen" @close="onClose">
        <ModalHeader>
            <ModalTitle>
                {{ __('resources.user.phone_verification.title') }}
            </ModalTitle>
            <ModalDescription class="text-center md:text-start" />
        </ModalHeader>

        <form @submit.prevent="verify()">
            <div class="max-h-[70vh] space-y-5 overflow-y-auto p-4">
                <div>
                    <InputLabel :value="__('resources.user.attributes.otp_code')" for="otp_code" class="sr-only" />

                    <div class="flex flex-row items-center justify-center gap-2">
                        <TextInput
                            v-for="(item, index) in 4"
                            :key="index"
                            :id="`otp_code_${index}`"
                            type="text"
                            maxlength="1"
                            inputmode="numeric"
                            dir="ltr"
                            placeholder="0"
                            class="spin-button-none size-12 text-center font-mono text-2xl"
                            :model-value="otpCode[index]"
                            @input="(e: Event) => handleOtpInput(e, index)"
                            @keydown="(e: KeyboardEvent) => handleOtpKeydown(e, index)"
                            :ref="(el) => (otpInputs[index] = el as OtpInputInstance | null)"
                        />
                    </div>

                    <div v-if="verificationHttp.errors.otp_code" class="mt-2 text-sm text-red-600">
                        {{ verificationHttp.errors.otp_code }}
                    </div>
                </div>

                <div
                    class="cursor-pointer text-center text-blue-700 hover:underline dark:text-blue-400 dark:hover:text-blue-500"
                    @click="send"
                >
                    {{
                        sendHttp.processing
                            ? __('resources.user.phone_verification.sending')
                            : __('resources.user.phone_verification.send')
                    }}
                </div>
            </div>

            <ModalFooter>
                <Button
                    color="secondary"
                    variant="outlined"
                    type="button"
                    @click="slotProps.close"
                    :value="__('global.closure.cancel')"
                />

                <Button
                    type="submit"
                    :value="__('resources.user.phone_verification.verify')"
                    :disabled="verificationHttp.processing || otpCode.join('').length !== 4"
                />
            </ModalFooter>
        </form>
    </Modal>
</template>
