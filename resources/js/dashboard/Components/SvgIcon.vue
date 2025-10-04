<script setup lang="ts">
import { defineAsyncComponent, onMounted, ref } from 'vue';

const props = defineProps<{
    name: string;
}>();

const icon = ref(null);

const splitIcon = (icon: string): [string, string] => {
    return icon.split('/') as [string, string];
};

const fallbackIconImport = async () => {
    return await import('./../../../svg/icons/image.svg');
};

const evaluateIcon = () => {
    return defineAsyncComponent(async () => {
        try {
            const [set, file] = splitIcon(props.name);

            return await import(`./../../../svg/${set}/${file}.svg`);
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
