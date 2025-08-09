<script setup lang="ts">
import MarkdownIt from 'markdown-it';
import { computed, h, nextTick, onMounted, ref, render, watch } from 'vue';
import CodeBlock from './CodeBlock.vue';

const props = defineProps<{
    markdown: string;
}>();

const codeBlocks = ref<Array<{ id: string; code: string; language?: string }>>([]);

const md = new MarkdownIt({
    html: true,
    linkify: true,
    typographer: true,
    breaks: true,
    highlight: function (code: string, language: string): string {
        const blockId = `code-${Date.now()}-${Math.random()}`;
        codeBlocks.value.push({
            code: code,
            language: language || undefined,
            id: blockId,
        });
        return `<pre id="${blockId}" class="overflow-visible"></pre>`;
    },
});

const parsedHtml = computed(() => {
    return md.render(props.markdown);
});

const replaceCodeBlocks = () => {
    codeBlocks.value.forEach((codeBlock) => {
        const el = document.getElementById(codeBlock.id);
        if (!el) return;

        const vnode = h(CodeBlock, {
            code: codeBlock.code,
            language: codeBlock.language,
        });

        render(vnode, el);
    });
};

watch(parsedHtml, async () => {
    await nextTick();
    replaceCodeBlocks();
});

onMounted(async () => {
    await nextTick();
    replaceCodeBlocks();
});
</script>

<template>
    <div class="prose dark:prose-invert max-w-full" v-html="parsedHtml"></div>
</template>
