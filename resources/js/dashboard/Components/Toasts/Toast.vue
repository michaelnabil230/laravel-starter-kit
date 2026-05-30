<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
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
                <SvgIcon v-if="toast.type === 'info'" name="icons/info" class="mt-0.5 size-4 shrink-0 text-blue-500" />
                <SvgIcon
                    v-if="toast.type === 'warning'"
                    name="icons/warning"
                    class="mt-0.5 size-4 shrink-0 text-yellow-500"
                />
                <SvgIcon
                    v-if="toast.type === 'danger'"
                    name="icons/danger"
                    class="mt-0.5 size-4 shrink-0 text-red-500"
                />
                <SvgIcon
                    v-if="toast.type === 'success'"
                    name="icons/check"
                    class="mt-0.5 size-4 shrink-0 text-blue-500"
                />
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
                    <SvgIcon name="icons/close" class="size-4 shrink-0" />
                </button>
            </div>
        </div>
    </div>
</template>
