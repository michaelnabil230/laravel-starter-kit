<script setup lang="ts">
import { useForwardProps } from 'reka-ui';
import { ref } from 'vue';
import TextInput from './TextInput.vue';
import { type InputWithDefaultValueProps } from './types';

const props = withDefaults(defineProps<InputWithDefaultValueProps>(), {
    hasError: false,
});

const forwardedProps = useForwardProps(props);

const model = defineModel<string | null>();

if (model.value === undefined) {
    model.value = props.defaultValue ?? null;
}

const toggle = ref(false);
</script>

<template>
    <TextInput v-model="model" :type="toggle ? 'text' : 'password'" v-bind="forwardedProps" placeholder="********">
        <template #icon>
            <button
                type="button"
                @click="toggle = !toggle"
                class="cursor-pointer text-gray-400 focus:text-blue-700 focus:outline-hidden dark:text-neutral-600 dark:focus:text-blue-500"
            >
                <svg
                    class="size-4 shrink-0"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path v-if="!toggle" d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                    <path
                        v-if="!toggle"
                        d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"
                    />
                    <path v-if="!toggle" d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                    <line v-if="!toggle" x1="2" x2="22" y1="2" y2="22" />
                    <path v-if="toggle" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                    <circle v-if="toggle" cx="12" cy="12" r="3" />
                </svg>
            </button>
        </template>
    </TextInput>
</template>
