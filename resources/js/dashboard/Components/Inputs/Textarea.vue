<script setup lang="ts">
import { onMounted, ref } from 'vue';

const model = defineModel<string>({ required: true });

const input = ref<HTMLInputElement | null>(null);

withDefaults(
    defineProps<{
        hasError?: boolean;
    }>(),
    {
        hasError: false,
    },
);

defineOptions({
    inheritAttrs: false,
});

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value?.focus();
    }
});

defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
    <div class="relative">
        <textarea
            ref="input"
            v-bind="$attrs"
            v-model="model"
            :class="{
                '!focus:border-red-500 !focus:ring-red-500 !border-red-500': hasError,
            }"
            class="block w-full rounded-lg border-gray-200 px-3 py-2 text-sm placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-transparent dark:text-neutral-300 dark:placeholder:text-white/60 dark:focus:ring-neutral-600"
        ></textarea>

        <div class="absolute inset-y-0 end-0 z-20 flex cursor-pointer items-center gap-2 pe-4">
            <slot name="icon" />

            <svg
                v-if="hasError"
                class="size-4 shrink-0 text-red-500"
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
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" x2="12" y1="8" y2="12"></line>
                <line x1="12" x2="12.01" y1="16" y2="16"></line>
            </svg>
        </div>
    </div>
</template>
