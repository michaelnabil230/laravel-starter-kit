<script setup lang="ts">
import Button from '@/dashboard/components/button/Button.vue';
import Radio from '@/dashboard/components/inputs/Radio.vue';
import Modal from '@/dashboard/components/modal/Modal.vue';
import ModalDescription from '@/dashboard/components/modal/ModalDescription.vue';
import ModalFooter from '@/dashboard/components/modal/ModalFooter.vue';
import ModalHeader from '@/dashboard/components/modal/ModalHeader.vue';
import ModalTitle from '@/dashboard/components/modal/ModalTitle.vue';
import { ref } from 'vue';

defineProps<{
    resource: string;
}>();

const emit = defineEmits<{
    (event: 'close'): void;
    (event: 'confirm', type: string): void;
}>();

const modal = defineModel<boolean>({ required: true });

const type = ref<string>('all');
</script>

<template>
    <Modal v-model="modal" size="xl" @closed="emit('close')">
        <!-- Header -->
        <ModalHeader>
            <ModalTitle>
                {{
                    __('export.resource', {
                        resource: __('resources.' + resource + '.plural'),
                    })
                }}
            </ModalTitle>
            <ModalDescription />
        </ModalHeader>
        <!-- End Header -->

        <!-- Body -->
        <div
            class="overflow-y-auto [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-stone-300 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 [&::-webkit-scrollbar-track]:bg-stone-100 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 [&::-webkit-scrollbar]:h-2"
        >
            <div class="p-4">
                <!-- List Item -->
                <div
                    class="mb-5 border-b border-stone-200 pb-5 last:mb-0 last:border-b-0 last:pb-0 dark:border-neutral-700"
                >
                    <ModalDescription class="mb-3 text-sm text-stone-500 dark:text-neutral-500">
                        {{ __('export.model.title') }}
                    </ModalDescription>

                    <label class="text-sm text-stone-500 dark:text-neutral-500">
                        {{ __('export.label') }}
                    </label>

                    <div class="mt-2 space-y-2">
                        <!-- Radio -->
                        <div class="flex items-center">
                            <Radio :id="'all_' + resource" value="all" v-model="type" checked />
                            <label :for="'all_' + resource" class="ms-3 text-sm text-stone-800 dark:text-neutral-400">
                                {{
                                    __('export.model.all_resource', {
                                        resource: __('resources.' + resource + '.plural'),
                                    })
                                }}
                            </label>
                        </div>
                        <!-- End Radio -->

                        <!-- Radio -->
                        <div class="flex items-center">
                            <Radio :id="'current_page_' + resource" value="current_page" v-model="type" />
                            <label
                                :for="'current_page_' + resource"
                                class="ms-3 text-sm text-stone-800 dark:text-neutral-400"
                            >
                                {{ __('export.model.current_page') }}
                            </label>
                        </div>
                        <!-- End Radio -->
                    </div>
                </div>
                <!-- End List Item -->
            </div>
        </div>
        <!-- End Body -->

        <!-- Footer -->
        <ModalFooter>
            <Button color="secondary" variant="outlined" @click="emit('close')" :value="__('global.closure.close')" />
            <Button @click="emit('confirm', type)" :value="__('export.model.confirm')" />
        </ModalFooter>
        <!-- End Footer -->
    </Modal>
</template>
