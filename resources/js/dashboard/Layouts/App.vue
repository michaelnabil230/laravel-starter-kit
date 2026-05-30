<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ConfigProvider } from 'reka-ui';
import { computed } from 'vue';
import initialSharedData from '../mixins/initialSharedData';

defineProps<{
    title?: string;
}>();

const dir = computed(() => (document.dir === 'rtl' ? 'rtl' : 'ltr'));

const appName = initialSharedData('appName');
</script>

<template>
    <ConfigProvider :dir="dir">
        <Head :title="title ? `${title} - ${appName}` : appName" />

        <slot />
    </ConfigProvider>
</template>
