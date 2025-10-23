<script setup lang="ts">
import { defineAsyncComponent, onMounted, shallowRef } from 'vue';

const props = defineProps<{
    name: string;
}>();

const icon = shallowRef(null);

const fallbackIconImport = async () => {
    return await import('./../../../svg/icons/image.svg');
};

const evaluateIcon = () => {
    return defineAsyncComponent(async () => {
        try {
            return await import(/* @vite-ignore */ `./../../../svg/${props.name}.svg`);
        } catch {
            return await fallbackIconImport();
        }
    });
};

onMounted(() => {
    icon.value = evaluateIcon();
});
</script>

<template>
    <component v-if="icon" :is="icon" />
</template>
