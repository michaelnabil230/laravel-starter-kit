<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import Textarea from '@/dashboard/Components/Inputs/Textarea.vue';
import Renderer from '@/dashboard/Components/Markdown/Renderer.vue';
import Modal from '@/dashboard/Components/Modal/Modal.vue';
import ModalDescription from '@/dashboard/Components/Modal/ModalDescription.vue';
import ModalHeader from '@/dashboard/Components/Modal/ModalHeader.vue';
import ModalTitle from '@/dashboard/Components/Modal/ModalTitle.vue';
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
const showCheatsheet = ref(false);

const closeCheatsheet = () => {
    showCheatsheet.value = false;
};

const togglePreview = () => {
    previewMode.value = !previewMode.value;
};
</script>

<template>
    <div
        class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-neutral-700 dark:bg-neutral-800"
        :class="{ '!border-red-500': hasError }"
    >
        <!-- Markdown Toolbar -->
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
                    <svg
                        class="size-4 shrink-0"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M14 12a4 4 0 0 0 0-8H6v8" />
                        <path d="M15 20a4 4 0 0 0 0-8H6v8Z" />
                    </svg>
                </button>
                <button
                    @click="handleItalic"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <svg
                        class="size-4 shrink-0"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <line x1="19" x2="10" y1="4" y2="4" />
                        <line x1="14" x2="5" y1="20" y2="20" />
                        <line x1="15" x2="9" y1="4" y2="20" />
                    </svg>
                </button>
                <button
                    @click="handleHeading"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <svg
                        class="size-4 shrink-0"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M6 4V20" />
                        <path d="M18 4V20" />
                        <path d="M6 12H18" />
                    </svg>
                </button>
                <button
                    @click="handleStrike"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <svg
                        class="size-4 shrink-0"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M16 4H9a3 3 0 0 0-2.83 4" />
                        <path d="M14 12a4 4 0 0 1 0 8H6" />
                        <line x1="4" x2="20" y1="12" y2="12" />
                    </svg>
                </button>
                <button
                    @click="handleLink"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <svg
                        class="size-4 shrink-0"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                    </svg>
                </button>
                <button
                    @click="handleOl"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <svg
                        class="size-4 shrink-0"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <line x1="10" x2="21" y1="6" y2="6" />
                        <line x1="10" x2="21" y1="12" y2="12" />
                        <line x1="10" x2="21" y1="18" y2="18" />
                        <path d="M4 6h1v4" />
                        <path d="M4 10h2" />
                        <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1" />
                    </svg>
                </button>
                <button
                    @click="handleUl"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <svg
                        class="size-4 shrink-0"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <line x1="8" x2="21" y1="6" y2="6" />
                        <line x1="8" x2="21" y1="12" y2="12" />
                        <line x1="8" x2="21" y1="18" y2="18" />
                        <line x1="3" x2="3.01" y1="6" y2="6" />
                        <line x1="3" x2="3.01" y1="12" y2="12" />
                        <line x1="3" x2="3.01" y1="18" y2="18" />
                    </svg>
                </button>
                <button
                    @click="handleBlockquote"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <svg
                        class="size-4 shrink-0"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M17 6H3" />
                        <path d="M21 12H8" />
                        <path d="M21 18H8" />
                        <path d="M3 12v6" />
                    </svg>
                </button>
                <button
                    @click="handleCode"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                >
                    <svg
                        class="size-4 shrink-0"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <polyline points="16 18 22 12 16 6" />
                        <polyline points="8 6 2 12 8 18" />
                    </svg>
                </button>

                <button
                    type="button"
                    @click="showCheatsheet = true"
                    class="inline-flex size-8 cursor-pointer items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                >
                    <svg
                        class="size-4 shrink-0"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle cx="12" cy="12" r="10" />
                        <path
                            d="M9.5 9.5C9.5 8.11929 10.6193 7 12 7C13.3807 7 14.5 8.11929 14.5 9.5C14.5 10.5353 13.8707 11.4236 12.9737 11.8033C12.4651 12.0186 12 12.4477 12 13V13.5"
                        />
                        <path d="M12.0001 17H12.009" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Textarea Editor -->

        <Textarea
            v-show="!previewMode"
            v-model="model"
            v-bind="forwardedProps"
            class="min-h-40 border-0 focus:ring-0"
        />

        <!-- Preview Mode -->
        <div v-show="previewMode" class="min-h-40 max-w-none px-3 py-2">
            <Renderer :markdown="model ?? __('markdown.nothing_to_preview')" />
        </div>

        <!-- Markdown Cheatsheet Modal -->
        <Modal v-model="showCheatsheet" @closed="closeCheatsheet">
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
