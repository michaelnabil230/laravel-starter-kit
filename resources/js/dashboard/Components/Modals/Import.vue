<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import FileUpload from '@/dashboard/Components/Inputs/FileUpload/FileUpload.vue';
import Modal from '@/dashboard/Components/Modal/Modal.vue';
import ModalDescription from '@/dashboard/Components/Modal/ModalDescription.vue';
import ModalFooter from '@/dashboard/Components/Modal/ModalFooter.vue';
import ModalHeader from '@/dashboard/Components/Modal/ModalHeader.vue';
import ModalTitle from '@/dashboard/Components/Modal/ModalTitle.vue';
import { App } from '@/dashboard/types/app';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    resource: string;
}>();

const emit = defineEmits<{
    (event: 'close'): void;
    (event: 'confirm', media: App.Models.Media | null): void;
}>();

const modal = defineModel<boolean>({ required: true });

const form = useForm({
    media: null,
});

const exampleSheet = computed(() => `/dashboard/example-import/${props.resource}.xlsx`);
</script>

<template>
    <Modal v-model="modal" @closed="emit('close')">
        <!-- Header -->
        <ModalHeader>
            <ModalTitle>
                {{
                    __('import.resource', {
                        resource: __('resources.' + resource + '.plural'),
                    })
                }}
            </ModalTitle>
            <ModalDescription />
        </ModalHeader>
        <!-- End Header -->
        <!-- Body -->
        <div
            class="overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-stone-300 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 [&::-webkit-scrollbar-track]:bg-stone-100 dark:[&::-webkit-scrollbar-track]:bg-neutral-700"
        >
            <div class="space-y-4 p-4">
                <FileUpload
                    id="media"
                    v-model="form.media"
                    :acceptedFileTypes="[
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-excel',
                    ]"
                />

                <p class="block text-sm text-stone-500 dark:text-neutral-400">
                    <a
                        :href="exampleSheet"
                        target="_blank"
                        download="example.xlsx"
                        class="text-sm font-medium text-blue-700 decoration-2 hover:underline focus:underline focus:outline-hidden dark:text-blue-400 dark:hover:text-blue-500"
                    >
                        {{ __('import.model.example.first') }}
                    </a>
                    {{ __('import.model.example.second') }}
                </p>
            </div>
        </div>
        <!-- End Body -->

        <!-- Footer -->
        <ModalFooter>
            <Button color="secondary" variant="outlined" @click="emit('close')" :value="__('global.closure.close')" />
            <Button @click="emit('confirm', form.media)" :value="__('import.model.confirm')" />
        </ModalFooter>
        <!-- End Footer -->
    </Modal>
</template>
