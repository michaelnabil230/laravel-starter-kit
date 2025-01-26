<script setup lang="ts">
import * as Buttons from '@/dashboard/Components/Buttons/Index';
import Modal from './Modal.vue';

const emit = defineEmits(['close', 'confirm']);

defineProps<{
    resource: string;
}>();
</script>

<template>
    <div :data-hs-overlay="'#export-' + resource"></div>
    <Modal :name="'export-' + resource" size="xl" @closed="emit('close')">
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-stone-200 px-4 py-3 dark:border-neutral-700">
            <h3 :id="'export-' + resource + '-label'" class="font-semibold text-stone-800 dark:text-neutral-200">
                {{
                    __('export.resource', {
                        resource: __('resources.' + resource + '.plural'),
                    })
                }}
            </h3>
            <button
                @click="emit('close')"
                class="inline-flex size-8 items-center justify-center gap-x-2 rounded-full border border-transparent bg-stone-100 text-stone-800 hover:bg-stone-200 focus:bg-stone-200 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:bg-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-600 dark:focus:bg-neutral-600"
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
                    <p class="mb-3 text-sm text-stone-500 dark:text-neutral-500">
                        {{ __('export.model.title') }}
                    </p>

                    <label class="text-sm text-stone-500 dark:text-neutral-500">
                        {{ __('export.label') }}
                    </label>

                    <div class="mt-2 space-y-2">
                        <!-- Radio -->
                        <div class="flex items-center">
                            <input
                                type="radio"
                                name="exporter"
                                class="shrink-0 rounded-full border-stone-300 text-blue-700 focus:ring-blue-700 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-500 dark:bg-neutral-800 dark:checked:border-blue-500 dark:checked:bg-blue-500 dark:focus:ring-offset-neutral-800"
                                :id="'current_page_' + resource"
                                checked
                            />
                            <label
                                :for="'current_page_' + resource"
                                class="ms-3 text-sm text-stone-800 dark:text-neutral-400"
                            >
                                {{ __('export.model.current_page') }}
                            </label>
                        </div>
                        <!-- End Radio -->

                        <!-- Radio -->
                        <div class="flex items-center">
                            <input
                                type="radio"
                                name="exporter"
                                class="shrink-0 rounded-full border-stone-300 text-blue-700 focus:ring-blue-700 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-500 dark:bg-neutral-800 dark:checked:border-blue-500 dark:checked:bg-blue-500 dark:focus:ring-offset-neutral-800"
                                :id="'all_' + resource"
                            />
                            <label :for="'all_' + resource" class="ms-3 text-sm text-stone-800 dark:text-neutral-400">
                                {{
                                    __('export.model.all_resource', {
                                        resource: __('resources.' + resource + '.plural'),
                                    })
                                }}
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
        <div class="flex justify-end gap-x-2 p-4">
            <Buttons.Solid.Primary @click="emit('confirm')" :value="__('export.model.confirm')" />
        </div>
        <!-- End Footer -->
    </Modal>
</template>
