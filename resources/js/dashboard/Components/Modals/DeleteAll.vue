<script setup lang="ts">
import * as Buttons from '@/dashboard/Components/Buttons/Index';
import Modal from './Modal.vue';

const emit = defineEmits(['close', 'confirm']);

defineProps<{
    resource: string;
}>();
</script>

<template>
    <div :data-hs-overlay="'#delete-all-' + resource + '-records'"></div>
    <Modal
        :name="'delete-all-' + resource + '-records'"
        size="2xl"
        class="relative overflow-hidden dark:bg-neutral-900"
        @closed="emit('close')"
    >
        <div class="absolute end-2 top-2">
            <button
                @click="emit('close')"
                class="inline-flex size-8 items-center justify-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:bg-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-600 dark:focus:bg-neutral-600"
                aria-label="Close"
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

        <div class="overflow-y-auto p-4 sm:p-10">
            <div class="flex gap-x-4 md:gap-x-7">
                <!-- Icon -->
                <span
                    class="inline-flex size-[46px] shrink-0 items-center justify-center rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:border-red-600 dark:bg-red-700 dark:text-red-100 sm:h-[62px] sm:w-[62px]"
                >
                    <svg
                        class="size-5 shrink-0"
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
                    <h3
                        :id="'delete-all-' + resource + '-records-label'"
                        class="mb-2 text-xl font-bold text-gray-800 dark:text-neutral-200"
                    >
                        {{
                            __('models.delete_all.title', {
                                resource: __('resources.' + resource + '.singular'),
                            })
                        }}
                    </h3>
                    <p class="text-gray-500 dark:text-neutral-500">
                        {{ __('models.delete_all.description') }}
                    </p>
                </div>
            </div>
        </div>

        <div
            class="flex items-center justify-end gap-x-2 border-t bg-gray-50 px-4 py-3 dark:border-neutral-800 dark:bg-neutral-950"
        >
            <Buttons.White.White @click="emit('close')" :value="__('global.closure.close')" />
            <Buttons.Solid.Danger @click="emit('confirm')" :value="__('global.crud.delete')" />
        </div>
    </Modal>
</template>
