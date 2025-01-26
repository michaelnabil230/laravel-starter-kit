<script setup lang="ts">
import { type HTMLAttributes } from 'vue';
import TextInput from './TextInput.vue';

withDefaults(
    defineProps<{
        defaultValue?: string | number;
        class?: HTMLAttributes['class'];
        hasError?: boolean;
    }>(),
    {
        hasError: false,
    },
);
</script>

<template>
    <TextInput v-bind="$attrs" type="time" />
</template>
