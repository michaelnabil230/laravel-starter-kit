<script setup lang="ts">
import ModalClose from './ModalClose.vue';
</script>

<template>
    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-2.5 dark:border-neutral-700">
        <slot />

        <ModalClose />
    </div>
</template>
