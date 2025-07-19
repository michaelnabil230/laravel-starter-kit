<script setup lang="ts">
import { ref } from 'vue';

const props = withDefaults(
    defineProps<{
        text: string;
        as?: string;
        selectAll?: boolean;
    }>(),
    {
        as: 'div',
        selectAll: true,
    },
);

defineSlots<{
    default(props: { error: boolean; success: boolean }): void;
}>();

const emit = defineEmits<{
    (event: 'success'): void;
    (event: 'error'): void;
}>();

const success = ref(false);

const error = ref(false);

const copy = () => {
    window.navigator.clipboard
        .writeText(props.text)
        .then(() => {
            emit('success');
            success.value = true;
            setTimeout(() => (success.value = false), 3000);
        })
        .catch(() => {
            emit('error');
            error.value = true;
            setTimeout(() => (error.value = false), 3000);
        });
};
</script>

<template>
    <component :is="as" class="inline-flex items-center justify-center gap-2" :class="{ 'select-all': selectAll }">
        <slot :error="error" :success="success" />

        <svg
            @click="copy"
            v-if="!success"
            class="size-4 cursor-pointer"
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
            <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
        </svg>

        <svg
            v-else
            class="size-4 text-blue-600"
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
            <polyline points="20 6 9 17 4 12" />
        </svg>
    </component>
</template>
