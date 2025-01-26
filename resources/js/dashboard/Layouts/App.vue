<script setup lang="ts">
import { ConfigProvider } from 'reka-ui';
import { computed } from 'vue';

const dir = computed(() => (document.dir === 'rtl' ? 'rtl' : 'ltr'));
</script>

<template>
    <ConfigProvider :dir="dir">
        <slot />
    </ConfigProvider>
</template>
