<script setup lang="ts">
import useToasts from '@/dashboard/composables/useToasts';
import { Toast } from '@/dashboard/types/toast';
import { onMounted } from 'vue';

const toasts = useToasts();

const props = defineProps<{
    toast: Toast;
}>();

onMounted(() => {
    setTimeout(() => remove(), 5000);
});

const remove = () => toasts.remove(props.toast.id);
</script>

<template>
    <div
        class="rounded-xl border border-gray-200 bg-white shadow-lg dark:border-neutral-700 dark:bg-neutral-800"
        role="alert"
        tabindex="-1"
        aria-labelledby="toast-label"
    >
        <div class="flex p-4">
            <div class="shrink-0">
                <svg
                    v-if="toast.type === 'info'"
                    class="mt-0.5 size-4 shrink-0 text-blue-500"
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    viewBox="0 0 16 16"
                >
                    <path
                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"
                    />
                </svg>

                <svg
                    v-if="toast.type === 'warning'"
                    class="mt-0.5 size-4 shrink-0 text-yellow-500"
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    viewBox="0 0 16 16"
                >
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"
                    />
                </svg>

                <svg
                    v-if="toast.type === 'danger'"
                    class="mt-0.5 size-4 shrink-0 text-red-500"
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    viewBox="0 0 16 16"
                >
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"
                    />
                </svg>

                <svg
                    v-if="toast.type === 'success'"
                    class="mt-0.5 size-4 shrink-0 text-blue-500"
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    viewBox="0 0 16 16"
                >
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"
                    />
                </svg>
            </div>
            <div class="ms-3 flex w-full justify-between">
                <p id="toast-label" class="text-sm text-gray-700 dark:text-neutral-400">
                    {{ toast.message }}
                </p>

                <button
                    type="button"
                    @click="remove()"
                    class="inline-flex size-5 shrink-0 cursor-pointer items-center justify-center rounded-lg text-white opacity-50 hover:text-white hover:opacity-100 focus:opacity-100 focus:outline-hidden"
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
        </div>
    </div>
</template>
