<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import { Modal, ModalClose, ModalDescription, ModalFooter, ModalTitle } from '@/dashboard/Components/Modal/Index';
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';

defineProps<{
    resource: string;
}>();

const emit = defineEmits(['close', 'confirm']);
</script>

<template>
    <Modal :name="'delete-' + resource" v-slot="slotProps" size="2xl" @close="emit('close')">
        <div class="absolute inset-e-4 top-3">
            <ModalClose class="size-7" />
        </div>

        <div class="overflow-y-auto p-4 sm:p-10">
            <div class="flex flex-col items-center gap-4 md:flex-row md:gap-7">
                <span
                    class="inline-flex size-16 shrink-0 items-center justify-center rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:border-red-600 dark:bg-red-700 dark:text-red-100"
                >
                    <SvgIcon name="icons/alert" class="size-6 shrink-0" />
                </span>

                <div class="grow">
                    <ModalTitle class="mb-2 text-center text-xl font-bold md:text-start">
                        {{ __('models.delete.title', { resource: __('resources.' + resource + '.singular') }) }}
                    </ModalTitle>
                    <ModalDescription class="text-center md:text-start">
                        {{ __('models.delete.description') }}
                    </ModalDescription>
                </div>
            </div>
        </div>

        <ModalFooter class="bg-gray-50 dark:bg-neutral-950">
            <Button color="secondary" variant="outlined" @click="slotProps.close" :value="__('global.closure.close')" />
            <Button color="danger" @click="emit('confirm')" :value="__('global.crud.delete')" />
        </ModalFooter>
    </Modal>
</template>
