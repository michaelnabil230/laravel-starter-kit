<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import { Modal, ModalDescription, ModalFooter, ModalHeader, ModalTitle } from '@/dashboard/Components/Modal/Index';
import useLocalization from '@/dashboard/composables/useLocalization';
import BaseCreate from './BaseCreate.vue';
import { ref } from 'vue';
import { Option } from '@/dashboard/Components/Inputs/types.js';
import type { ModalInstance } from '@/dashboard/Components/Modal/types';

const { __ } = useLocalization();

const modal = ref<ModalInstance>();

const baseCreateRef = ref<InstanceType<typeof BaseCreate> | null>(null);

const emit = defineEmits<{
    created: [user: Option];
}>();

const handleSubmit = () => {
    baseCreateRef.value?.submit();
};

const created = (user: Option) => {
    emit('created', user);
    modal.value?.close();
};
</script>

<template>
    <Modal ref="modal" name="create-user" v-slot="slotProps" size="3xl">
        <ModalHeader>
            <ModalTitle>
                {{
                    __('global.resource.create', {
                        resource: __('resources.user.singular'),
                    })
                }}
            </ModalTitle>
            <ModalDescription />
        </ModalHeader>

        <form @submit.prevent="handleSubmit()">
            <BaseCreate ref="baseCreateRef" :asModal="true" @created="created" />

            <ModalFooter>
                <Button
                    color="secondary"
                    variant="outlined"
                    type="button"
                    @click="slotProps.close()"
                    :value="__('global.closure.cancel')"
                />

                <Button type="submit" :value="__('global.crud.create')" :disabled="baseCreateRef?.form?.processing" />
            </ModalFooter>
        </form>
    </Modal>
</template>
