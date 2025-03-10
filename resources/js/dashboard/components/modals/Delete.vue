<script setup lang="ts">
import Button from '@/dashboard/components/button/Button.vue';
import Modal from '@/dashboard/components/modal/Modal.vue';
import ModalClose from '@/dashboard/components/modal/ModalClose.vue';
import ModalDescription from '@/dashboard/components/modal/ModalDescription.vue';
import ModalFooter from '@/dashboard/components/modal/ModalFooter.vue';
import ModalTitle from '@/dashboard/components/modal/ModalTitle.vue';

defineProps<{
    resource: string;
}>();

const emit = defineEmits(['close', 'confirm']);

const modal = defineModel<boolean>({ required: true });
</script>

<template>
    <Modal v-model="modal" @closed="emit('close')">
        <div class="absolute end-4 top-3">
            <ModalClose class="size-7" />
        </div>

        <div class="overflow-y-auto p-4 sm:p-10">
            <div class="flex flex-col items-center md:flex-row gap-4 md:gap-7">
                <!-- Icon -->
                <span
                    class="inline-flex size-16 shrink-0 items-center justify-center rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:border-red-600 dark:bg-red-700 dark:text-red-100"
                >
                    <svg
                        class="size-6 shrink-0"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        viewBox="0 0 16 16"
                    >
                        <path
                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"
                        />
                    </svg>
                </span>
                <!-- End Icon -->

                <div class="grow">
                    <ModalTitle class="mb-2 text-xl font-bold md:text-start text-center">
                        {{ __('models.delete.title', { resource: __('resources.' + resource + '.singular') }) }}
                    </ModalTitle>
                    <ModalDescription class="md:text-start text-center">
                        {{ __('models.delete.description') }}
                    </ModalDescription>
                </div>
            </div>
        </div>

        <ModalFooter class="bg-gray-50 dark:bg-neutral-950">
            <Button color="secondary" variant="outlined" @click="emit('close')" :value="__('global.closure.close')" />
            <Button color="danger" @click="emit('confirm')" :value="__('global.crud.delete')" />
        </ModalFooter>
    </Modal>
</template>
