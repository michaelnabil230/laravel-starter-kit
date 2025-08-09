<script setup lang="ts">
import useLocalization from '@/dashboard/composables/useLocalization';
import hljs from 'highlight.js';
import 'highlight.js/styles/github-dark-dimmed.css';
import { computed, ref } from 'vue';

const { __ } = useLocalization();

const props = defineProps<{
    code: string;
    language?: string;
}>();

const codeRef = ref<HTMLElement>();
const isCopied = ref(false);

const highlightedCode = computed(() => {
    if (props.language && hljs.getLanguage(props.language)) {
        try {
            const result = hljs.highlight(props.code, { language: props.language });
            return result.value;
        } catch {
            return props.code;
        }
    }

    return props.code;
});

const copyCode = () => {
    navigator.clipboard.writeText(props.code).then(() => {
        isCopied.value = true;
        setTimeout(() => (isCopied.value = false), 2000);
    });
};
</script>

<template>
    <div class="rounded-lg bg-neutral-800 contain-inline-size">
        <div class="flex h-9 items-center justify-between rounded-t-lg bg-black px-4 py-2 font-sans text-xs text-white">
            {{ language }}
        </div>
        <div class="sticky top-24">
            <div class="absolute end-2 bottom-0 flex h-9 items-center pe-2">
                <div class="font-sans text-xs text-white">
                    <button
                        type="button"
                        @click="copyCode"
                        class="flex cursor-pointer items-center gap-1 py-1 select-none"
                    >
                        <svg
                            v-if="!isCopied"
                            class="size-4"
                            xmlns="http://www.w3.org/2000/svg"
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
                            class="size-4"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <polyline points="20 6 9 17 4 12" />
                        </svg>

                        {{ isCopied ? __('global.copied') : __('global.copy_to_clipboard') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="overflow-y-auto p-4" dir="ltr">
            <code ref="codeRef" class="bg-neutral-800! p-0! whitespace-pre!" v-html="highlightedCode" />
        </div>
    </div>
</template>
