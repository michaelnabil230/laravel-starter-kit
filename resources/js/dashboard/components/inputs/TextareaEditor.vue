<script setup lang="ts">
import { Editor } from '@tiptap/core';
import Blockquote from '@tiptap/extension-blockquote';
import Bold from '@tiptap/extension-bold';
import BulletList from '@tiptap/extension-bullet-list';
import { Color } from '@tiptap/extension-color';
import Link from '@tiptap/extension-link';
import ListItem from '@tiptap/extension-list-item';
import OrderedList from '@tiptap/extension-ordered-list';
import Paragraph from '@tiptap/extension-paragraph';
import Placeholder from '@tiptap/extension-placeholder';
import TextAlign from '@tiptap/extension-text-align';
import TextStyle from '@tiptap/extension-text-style';
import Underline from '@tiptap/extension-underline';
import StarterKit from '@tiptap/starter-kit';
import { onBeforeUnmount, onMounted } from 'vue';

let editor: Editor;

const props = withDefaults(
    defineProps<{
        defaultValue?: string;
        hasError?: boolean;
        placeholder: string;
        autofocus?: boolean;
        editable?: boolean;
    }>(),
    {
        hasError: false,
        autofocus: false,
        editable: true,
    },
);

const model = defineModel<string | null>();

if (model.value === undefined) {
    model.value = props.defaultValue;
}

defineExpose({ focus: () => editor.commands.focus() });

onMounted(() => {
    editor = new Editor({
        element: document.querySelector('#editor-tiptap [data-editor-field]') as HTMLElement,
        editorProps: {
            attributes: {
                class: 'relative min-h-40 p-3',
            },
        },
        autofocus: props.autofocus,
        editable: props.editable,
        onUpdate: ({ editor }) => {
            model.value = editor.getHTML();
        },
        content: model.value,
        extensions: [
            StarterKit.configure(),
            Placeholder.configure({
                placeholder: props.placeholder,
                emptyNodeClass: 'text-gray-400 dark:text-white/60',
            }),
            Paragraph.configure({
                HTMLAttributes: {
                    class: 'text-gray-400 text-neutral-300',
                },
            }),
            Bold.configure({
                HTMLAttributes: {
                    class: 'font-bold',
                },
            }),
            Underline,
            Color.configure({ types: [TextStyle.name, ListItem.name] }),
            TextStyle,
            TextAlign.configure({
                types: ['heading', 'paragraph'],
            }),
            Link.configure({
                HTMLAttributes: {
                    class: 'inline-flex items-center gap-x-1 text-blue-600 decoration-2 hover:underline focus:outline-hidden focus:underline font-medium dark:text-white',
                },
            }),
            BulletList.configure({
                HTMLAttributes: {
                    class: 'list-disc list-inside text-gray-800 dark:text-white',
                },
            }),
            OrderedList.configure({
                HTMLAttributes: {
                    class: 'list-decimal list-inside text-gray-800 dark:text-white',
                },
            }),
            ListItem.configure({
                HTMLAttributes: {
                    class: 'marker:text-sm',
                },
            }),
            Blockquote.configure({
                HTMLAttributes: {
                    class: 'relative border-s-4 ps-4 sm:ps-6 dark:border-neutral-700 sm:[&>p]:text-lg',
                },
            }),
        ],
    });
    const actions = [
        {
            id: '#editor-tiptap [data-editor-bold]',
            onClick: () => editor.chain().focus().toggleBold().run(),
        },
        {
            id: '#editor-tiptap [data-editor-italic]',
            onClick: () => editor.chain().focus().toggleItalic().run(),
        },
        {
            id: '#editor-tiptap [data-editor-underline]',
            onClick: () => editor.chain().focus().toggleUnderline().run(),
        },
        {
            id: '#editor-tiptap [data-editor-strike]',
            onClick: () => editor.chain().focus().toggleStrike().run(),
        },
        {
            id: '#editor-tiptap [data-editor-link]',
            onClick: () => {
                const url = window.prompt('URL');

                if (url === null) return;

                editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
            },
        },
        {
            id: '#editor-tiptap [data-editor-ol]',
            onClick: () => editor.chain().focus().toggleOrderedList().run(),
        },
        {
            id: '#editor-tiptap [data-editor-ul]',
            onClick: () => editor.chain().focus().toggleBulletList().run(),
        },
        {
            id: '#editor-tiptap [data-editor-blockquote]',
            onClick: () => editor.chain().focus().toggleBlockquote().run(),
        },
        {
            id: '#editor-tiptap [data-editor-color]',
            onClick: () => document.getElementById('color-picker')?.click(),
        },
        {
            id: '#editor-tiptap [data-editor-undo]',
            onClick: () => editor.chain().focus().undo().run(),
        },
        {
            id: '#editor-tiptap [data-editor-redo]',
            onClick: () => editor.chain().focus().redo().run(),
        },
        {
            id: '#editor-tiptap [data-editor-left]',
            onClick: () => editor.chain().focus().setTextAlign('left').run(),
        },
        {
            id: '#editor-tiptap [data-editor-center]',
            onClick: () => editor.chain().focus().setTextAlign('center').run(),
        },
        {
            id: '#editor-tiptap [data-editor-right]',
            onClick: () => editor.chain().focus().setTextAlign('right').run(),
        },
    ];

    actions.forEach(({ id, onClick }) => {
        const action = document.querySelector(id);

        if (action === null) return;

        action.addEventListener('click', onClick);
    });
});

const changeColor = (event: Event) => {
    editor
        .chain()
        .focus()
        .setColor((event.target as HTMLInputElement).value)
        .run();
};

onBeforeUnmount(() => editor.destroy());
</script>

<template>
    <div
        class="bg-white border border-gray-200 rounded-xl overflow-hidden dark:bg-neutral-800 dark:border-neutral-700"
        :class="{ '!border-red-500': hasError }"
    >
        <div id="editor-tiptap">
            <div
                class="sticky top-0 bg-white dark:bg-neutral-800 flex align-middle gap-x-0.5 border-b border-gray-200 p-2 dark:border-neutral-700"
            >
                <button
                    class="cursor-pointer size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:ring-neutral-600"
                    type="button"
                    data-editor-bold
                >
                    <svg
                        class="shrink-0 size-4"
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
                        <path d="M14 12a4 4 0 0 0 0-8H6v8"></path>
                        <path d="M15 20a4 4 0 0 0 0-8H6v8Z"></path>
                    </svg>
                </button>
                <button
                    class="cursor-pointer size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                    data-editor-italic
                >
                    <svg
                        class="shrink-0 size-4"
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
                        <line x1="19" x2="10" y1="4" y2="4"></line>
                        <line x1="14" x2="5" y1="20" y2="20"></line>
                        <line x1="15" x2="9" y1="4" y2="20"></line>
                    </svg>
                </button>
                <button
                    class="cursor-pointer size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                    data-editor-underline
                >
                    <svg
                        class="shrink-0 size-4"
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
                        <path d="M6 4v6a6 6 0 0 0 12 0V4"></path>
                        <line x1="4" x2="20" y1="20" y2="20"></line>
                    </svg>
                </button>
                <button
                    class="cursor-pointer size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                    data-editor-strike
                >
                    <svg
                        class="shrink-0 size-4"
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
                        <path d="M16 4H9a3 3 0 0 0-2.83 4"></path>
                        <path d="M14 12a4 4 0 0 1 0 8H6"></path>
                        <line x1="4" x2="20" y1="12" y2="12"></line>
                    </svg>
                </button>
                <button
                    class="cursor-pointer size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                    data-editor-link
                >
                    <svg
                        class="shrink-0 size-4"
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
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                </button>
                <button
                    class="cursor-pointer size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                    data-editor-ol
                >
                    <svg
                        class="shrink-0 size-4"
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
                        <line x1="10" x2="21" y1="6" y2="6"></line>
                        <line x1="10" x2="21" y1="12" y2="12"></line>
                        <line x1="10" x2="21" y1="18" y2="18"></line>
                        <path d="M4 6h1v4"></path>
                        <path d="M4 10h2"></path>
                        <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"></path>
                    </svg>
                </button>
                <button
                    class="cursor-pointer size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                    data-editor-ul
                >
                    <svg
                        class="shrink-0 size-4"
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
                        <line x1="8" x2="21" y1="6" y2="6"></line>
                        <line x1="8" x2="21" y1="12" y2="12"></line>
                        <line x1="8" x2="21" y1="18" y2="18"></line>
                        <line x1="3" x2="3.01" y1="6" y2="6"></line>
                        <line x1="3" x2="3.01" y1="12" y2="12"></line>
                        <line x1="3" x2="3.01" y1="18" y2="18"></line>
                    </svg>
                </button>
                <button
                    class="cursor-pointer size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                    data-editor-blockquote
                >
                    <svg
                        class="shrink-0 size-4"
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
                        <path d="M17 6H3"></path>
                        <path d="M21 12H8"></path>
                        <path d="M21 18H8"></path>
                        <path d="M3 12v6"></path>
                    </svg>
                </button>
                <button
                    class="cursor-pointer size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    :class="{
                        'bg-blue-500 text-white hover:text-gray-800': editor?.isActive({ textAlign: 'left' }) ?? false,
                    }"
                    type="button"
                    data-editor-left
                >
                    <svg
                        class="shrink-0 size-4"
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
                        <path d="M3 3H21"></path>
                        <path d="M3 9H11"></path>
                        <path d="M3 15H21"></path>
                        <path d="M3 21H11"></path>
                    </svg>
                </button>
                <button
                    class="cursor-pointer size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    :class="{
                        'bg-blue-500 text-white hover:text-gray-800':
                            editor?.isActive({ textAlign: 'center' }) ?? false,
                    }"
                    type="button"
                    data-editor-center
                >
                    <svg
                        class="shrink-0 size-4"
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
                        <path d="M3 3H21"></path>
                        <path d="M8 9H16"></path>
                        <path d="M3 15H21"></path>
                        <path d="M8 21H16"></path>
                    </svg>
                </button>
                <button
                    class="cursor-pointer size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    :class="{
                        'bg-blue-500 text-white hover:text-gray-800': editor?.isActive({ textAlign: 'right' }) ?? false,
                    }"
                    type="button"
                    data-editor-right
                >
                    <svg
                        class="shrink-0 size-4"
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
                        <path d="M3 3H21" />
                        <path d="M13 9H21" />
                        <path d="M3 15H21" />
                        <path d="M13 21H21" />
                    </svg>
                </button>
                <input id="color-picker" type="color" class="size-0" :onchange="changeColor" />
                <button
                    class="cursor-pointer size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                    data-editor-color
                >
                    <svg
                        class="shrink-0 size-4"
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
                        <path
                            d="M17.5798 9.71016C17.0765 9.57314 16.5468 9.5 16 9.5C13.4668 9.5 11.3002 11.0699 10.4202 13.2898M17.5798 9.71016C20.1271 10.4036 22 12.7331 22 15.5C22 18.8137 19.3137 21.5 16 21.5C14.4633 21.5 13.0615 20.9223 12 19.9722M17.5798 9.71016C17.851 9.02618 18 8.2805 18 7.5C18 4.18629 15.3137 1.5 12 1.5C8.68629 1.5 6 4.18629 6 7.5C6 8.2805 6.14903 9.02618 6.42018 9.71016M10.4202 13.2898C10.149 13.9738 10 14.7195 10 15.5C10 17.277 10.7725 18.8736 12 19.9722M10.4202 13.2898C8.59146 12.792 7.11029 11.451 6.42018 9.71016M6.42018 9.71016C3.87294 10.4036 2 12.7331 2 15.5C2 18.8137 4.68629 21.5 8 21.5C9.53671 21.5 10.9385 20.9223 12 19.9722"
                        />
                    </svg>
                </button>
                <button
                    class="cursor-pointer size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                    data-editor-undo
                >
                    <svg
                        class="shrink-0 size-4"
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
                        <path d="M3 8H15C18.3137 8 21 10.6863 21 14C21 17.3137 18.3137 20 15 20H11" />
                        <path
                            d="M7 4L5.8462 4.87652C3.94873 6.31801 3 7.03875 3 8C3 8.96125 3.94873 9.68199 5.8462 11.1235L7 12"
                        />
                    </svg>
                </button>
                <button
                    class="cursor-pointer size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    type="button"
                    data-editor-redo
                >
                    <svg
                        class="shrink-0 size-4"
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
                        <path d="M21 8H9C5.68629 8 3 10.6863 3 14C3 17.3137 5.68629 20 9 20H13" />
                        <path
                            d="M17 4L18.1538 4.87652C20.0513 6.31801 21 7.03875 21 8C21 8.96125 20.0513 9.68199 18.1538 11.1235L17 12"
                        />
                    </svg>
                </button>
            </div>

            <div class="h-40 overflow-auto" data-editor-field></div>
        </div>
    </div>
</template>
