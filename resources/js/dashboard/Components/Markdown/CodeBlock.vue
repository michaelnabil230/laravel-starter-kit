<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
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
            <div class="absolute inset-e-2 bottom-0 flex h-9 items-center pe-2">
                <div class="font-sans text-xs text-white">
                    <button
                        type="button"
                        @click="copyCode"
                        class="flex cursor-pointer items-center gap-1 py-1 select-none"
                    >
                        <SvgIcon v-if="!isCopied" name="icons/copy" class="size-4" />
                        <SvgIcon v-else name="icons/check" class="size-4" />

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
