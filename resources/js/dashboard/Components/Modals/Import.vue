<script setup lang="ts">
import * as Buttons from '@/dashboard/Components/Buttons/Index';
import Modal from './Modal.vue';

const emit = defineEmits(['close', 'confirm']);

defineProps<{
    resource: string;
}>();
</script>

<template>
    <div :data-hs-overlay="'#import-' + resource"></div>
    <Modal :name="'import-' + resource" size="xl" @closed="emit('close')">
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-stone-200 px-4 py-3 dark:border-neutral-700">
            <h3 :id="'import-' + resource + '-label'" class="font-semibold text-stone-800 dark:text-neutral-200">
                {{
                    __('import.resource', {
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
            class="overflow-y-auto [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-stone-300 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 [&::-webkit-scrollbar-track]:bg-stone-100 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 [&::-webkit-scrollbar]:w-2"
        >
            <div class="p-4">
                <label class="block text-sm text-stone-500 dark:text-neutral-400">
                    <a
                        href="#"
                        class="text-sm font-medium text-blue-700 decoration-2 hover:underline focus:underline focus:outline-none dark:text-blue-400 dark:hover:text-blue-500"
                    >
                        {{ __('import.model.example.first') }}
                    </a>
                    {{ __('import.model.example.second') }}.
                </label>

                <!-- Drag 'n Drop -->
                <div class="space-y-2">
                    <label class="mb-2 hidden text-sm font-medium text-stone-800 dark:text-neutral-200">
                        {{ __('import.model.upload_file') }}
                    </label>
                    <div
                        class="flex h-56 justify-center rounded-xl border border-dashed border-stone-300 bg-white p-12 dark:border-neutral-600 dark:bg-neutral-800"
                    >
                        <div class="text-center">
                            <svg
                                class="mx-auto w-16 text-stone-400 dark:text-neutral-400"
                                width="70"
                                height="46"
                                viewBox="0 0 70 46"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M6.05172 9.36853L17.2131 7.5083V41.3608L12.3018 42.3947C9.01306 43.0871 5.79705 40.9434 5.17081 37.6414L1.14319 16.4049C0.515988 13.0978 2.73148 9.92191 6.05172 9.36853Z"
                                    fill="currentColor"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    class="fill-white stroke-stone-400 dark:fill-neutral-800 dark:stroke-neutral-500"
                                />
                                <path
                                    d="M63.9483 9.36853L52.7869 7.5083V41.3608L57.6982 42.3947C60.9869 43.0871 64.203 40.9434 64.8292 37.6414L68.8568 16.4049C69.484 13.0978 67.2685 9.92191 63.9483 9.36853Z"
                                    fill="currentColor"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    class="fill-white stroke-stone-400 dark:fill-neutral-800 dark:stroke-neutral-500"
                                />
                                <rect
                                    x="17.0656"
                                    y="1.62305"
                                    width="35.8689"
                                    height="42.7541"
                                    rx="5"
                                    fill="currentColor"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    class="fill-white stroke-stone-400 dark:fill-neutral-800 dark:stroke-neutral-500"
                                />
                                <path
                                    d="M47.9344 44.3772H22.0655C19.3041 44.3772 17.0656 42.1386 17.0656 39.3772L17.0656 35.9161L29.4724 22.7682L38.9825 33.7121C39.7832 34.6335 41.2154 34.629 42.0102 33.7025L47.2456 27.5996L52.9344 33.7209V39.3772C52.9344 42.1386 50.6958 44.3772 47.9344 44.3772Z"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    class="stroke-stone-400 dark:stroke-neutral-500"
                                />
                                <circle
                                    cx="39.5902"
                                    cy="14.9672"
                                    r="4.16393"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    class="stroke-stone-400 dark:stroke-neutral-500"
                                />
                            </svg>

                            <div class="mt-4 flex flex-wrap justify-center text-sm leading-6 text-stone-600">
                                <span class="pe-1 font-medium text-stone-800 dark:text-neutral-200">
                                    {{ __('import.model.drag_drop') }}
                                </span>
                                <label
                                    for="file"
                                    class="relative cursor-pointer rounded-lg bg-white font-semibold text-blue-700 decoration-2 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-700 focus-within:ring-offset-2 hover:text-blue-700 hover:underline dark:bg-neutral-800 dark:text-blue-500 dark:hover:text-blue-700"
                                >
                                    <span>{{ __('browse') }}</span>
                                    <input id="file" type="file" class="sr-only" />
                                </label>
                            </div>

                            <p class="mt-1 text-xs text-stone-400 dark:text-neutral-400">
                                {{ __('import.model.extensions') }}
                            </p>
                        </div>
                    </div>
                </div>
                <!-- End Drag 'n Drop -->
            </div>
        </div>
        <!-- End Body -->

        <!-- Footer -->
        <div class="flex justify-end gap-x-2 p-4">
            <Buttons.Solid.Primary @click="emit('confirm')" :value="__('upload')" />
        </div>
        <!-- End Footer -->
    </Modal>
</template>
