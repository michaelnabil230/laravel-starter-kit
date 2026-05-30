<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import Textarea from '@/dashboard/Components/Inputs/Textarea.vue';
import Renderer from '@/dashboard/Components/Markdown/Renderer.vue';
import { Modal, ModalDescription, ModalHeader, ModalTitle } from '@/dashboard/Components/Modal/Index';
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
import { visitModal } from '@/dashboard/composables/modal/modalStack';
import useLocalization from '@/dashboard/composables/useLocalization';
import { useForwardProps } from 'reka-ui';
import { ref } from 'vue';
import { TextareaEditorProps } from './types';

const { __ } = useLocalization();

defineOptions({ inheritAttrs: false });

const props = withDefaults(defineProps<TextareaEditorProps>(), {
    hasError: false,
});

const forwardedProps = useForwardProps(props);

const model = defineModel<string | null>();

if (model.value === undefined) {
    model.value = props.defaultValue ?? null;
}

const insertOrWrapMarkdown = (before: string, after: string = before, placeholder = '', multiline = false) => {
    // For now, we'll just insert the markdown at the end of the current value
    // This is a simplified version without cursor position handling
    const currentValue = model.value ?? '';
    let insertText = '';

    if (multiline) {
        // For multiline (e.g., lists, blockquote), add before each line
        const lines = [placeholder];
        insertText = lines.map((line) => before + line).join('\n');
    } else {
        insertText = before + placeholder + after;
    }

    // Add to the end of current content
    const newValue = currentValue + (currentValue ? '\n' : '') + insertText;
    model.value = newValue;
};

const handleBold = () => insertOrWrapMarkdown('**', '**', 'bold');
const handleItalic = () => insertOrWrapMarkdown('*', '*', 'italic');
const handleStrike = () => insertOrWrapMarkdown('~~', '~~', 'strikethrough');
const handleLink = () => insertOrWrapMarkdown('[', '](url)', 'text');
const handleOl = () => insertOrWrapMarkdown('1. ', '', 'list item', true);
const handleUl = () => insertOrWrapMarkdown('- ', '', 'list item', true);
const handleBlockquote = () => insertOrWrapMarkdown('> ', '', 'quote', true);
const handleHeading = () => insertOrWrapMarkdown('### ', '', 'Heading');
const handleCode = () => insertOrWrapMarkdown('`', '`', 'code');

const previewMode = ref(false);

const togglePreview = () => {
    previewMode.value = !previewMode.value;
};
</script>

<template>
    <div
        class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-neutral-700 dark:bg-neutral-800"
        :class="{ 'border-red-500!': hasError }"
    >
        <div
            class="flex justify-between border-b border-gray-200 bg-white p-2 dark:border-neutral-700 dark:bg-neutral-800"
        >
            <Button
                type="button"
                size="small"
                variant="outlined"
                color="secondary"
                class="border-none px-2 py-1"
                @click="togglePreview"
            >
                {{ previewMode ? __('markdown.edit') : __('markdown.preview') }}
            </Button>

            <div v-if="!previewMode" class="flex gap-x-0.5">
                <button
                    @click="handleBold"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:border-blue-500 focus:ring-blue-500 dark:text-white dark:hover:bg-neutral-700 dark:focus:ring-neutral-600"
                    type="button"
                >
                    <SvgIcon name="icons/bold" class="size-4 shrink-0" />
                </button>
                <button
                    @click="handleItalic"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <SvgIcon name="icons/italic" class="size-4 shrink-0" />
                </button>
                <button
                    @click="handleHeading"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <SvgIcon name="icons/heading" class="size-4 shrink-0" />
                </button>
                <button
                    @click="handleStrike"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <SvgIcon name="icons/strike" class="size-4 shrink-0" />
                </button>
                <button
                    @click="handleLink"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <SvgIcon name="icons/link" class="size-4 shrink-0" />
                </button>
                <button
                    @click="handleOl"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <SvgIcon name="icons/ol" class="size-4 shrink-0" />
                </button>
                <button
                    @click="handleUl"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <SvgIcon name="icons/ul" class="size-4 shrink-0" />
                </button>
                <button
                    @click="handleBlockquote"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <SvgIcon name="icons/blockquote" class="size-4 shrink-0" />
                </button>
                <button
                    @click="handleCode"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <SvgIcon name="icons/code" class="size-4 shrink-0" />
                </button>

                <button
                    type="button"
                    @click="visitModal('#markdown-cheatsheet')"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                >
                    <SvgIcon name="icons/cheatsheet" class="size-4 shrink-0" />
                </button>
            </div>
        </div>

        <Textarea
            v-show="!previewMode"
            v-model="model"
            v-bind="forwardedProps"
            class="min-h-40 border-0 focus:ring-0"
        />

        <div v-show="previewMode" class="min-h-40 max-w-none px-3 py-2">
            <Renderer :markdown="model ?? __('markdown.nothing_to_preview')" />
        </div>

        <Modal name="markdown-cheatsheet">
            <ModalHeader>
                <ModalTitle>
                    {{ __('markdown.cheatsheet') }}
                </ModalTitle>
                <ModalDescription />
            </ModalHeader>
            <div class="space-y-6 p-4 text-gray-800 dark:text-white">
                <div>
                    <h3 class="mb-2 text-sm font-semibold text-gray-900 dark:text-neutral-100">
                        {{ __('markdown.headings') }}
                    </h3>
                    <ul class="ms-2 list-inside list-disc space-y-1 text-sm text-gray-800 dark:text-white">
                        <li><span># H1</span></li>
                        <li><span>## H2</span></li>
                        <li><span>### H3</span></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-2 text-sm font-semibold text-gray-900 dark:text-neutral-100">
                        {{ __('markdown.text_styles') }}
                    </h3>
                    <ul class="ms-2 list-inside list-disc space-y-1 text-sm text-gray-800 dark:text-white">
                        <li><span>**bold**</span> → <b>bold</b></li>
                        <li><span>*italic*</span> → <i>italic</i></li>
                        <li><span>~~strike~~</span> → <span class="line-through">strike</span></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-2 text-sm font-semibold text-gray-900 dark:text-neutral-100">
                        {{ __('markdown.code') }}
                    </h3>
                    <ul class="ms-2 list-inside list-disc space-y-1 text-sm text-gray-800 dark:text-white">
                        <li><span>`inline code`</span></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-2 text-sm font-semibold text-gray-900 dark:text-neutral-100">
                        {{ __('markdown.lists_links') }}
                    </h3>
                    <ul class="ms-2 list-inside list-disc space-y-1 text-sm text-gray-800 dark:text-white">
                        <li><span>- item</span> (unordered)</li>
                        <li><span>1. item</span> (ordered)</li>
                        <li><span>[text](url)</span> (link)</li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-2 text-sm font-semibold text-gray-900 dark:text-neutral-100">
                        {{ __('markdown.blockquotes') }}
                    </h3>
                    <ul class="ms-2 list-inside list-disc space-y-1 text-sm text-gray-800 dark:text-white">
                        <li><span>&gt; quote</span></li>
                    </ul>
                </div>
            </div>
        </Modal>
    </div>
</template>
