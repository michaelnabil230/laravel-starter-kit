<script setup lang="ts">
import { useForwardProps } from 'reka-ui';
import TextInput from './TextInput.vue';
import { type InputWithDefaultValueProps } from './types';

const props = withDefaults(defineProps<InputWithDefaultValueProps>(), {
    hasError: false,
});

const forwardedProps = useForwardProps(props);
</script>

<template>
    <TextInput v-bind="forwardedProps" type="date" />
</template>
