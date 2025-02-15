<script setup lang="ts">
import { computed, useAttrs } from 'vue';
import TextInput from './TextInput.vue';

defineOptions({
    inheritAttrs: false,
});

const props = defineProps<{
    ids?: String[];
}>();

const model = defineModel<string>({ required: true });

const attrs: any = useAttrs();

const ids = computed(() => {
    const attrId = attrs.id ? '#' + attrs.id : null;

    return [...(props.ids || []), attrId];
});
</script>

<template>
    <TextInput v-model="model" type="password" placeholder="********" v-bind="attrs">
        <template #icon>
            <button
                type="button"
                :data-hs-toggle-password="JSON.stringify({ target: ids })"
                class="text-gray-400 focus:text-blue-700 focus:outline-none dark:text-neutral-600 dark:focus:text-blue-500"
            >
                <svg
                    class="size-4 flex-shrink-0"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                    <path
                        class="hs-password-active:hidden"
                        d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"
                    />
                    <path
                        class="hs-password-active:hidden"
                        d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"
                    />
                    <line class="hs-password-active:hidden" x1="2" x2="22" y1="2" y2="22" />
                    <path class="hidden hs-password-active:block" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                    <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3" />
                </svg>
            </button>
        </template>
    </TextInput>
</template>
