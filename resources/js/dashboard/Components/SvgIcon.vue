<script setup lang="ts">
import { computed, type Component } from 'vue';

const props = defineProps<{
    name: string;
}>();

const icons = import.meta.glob<Component>('../../../svg/**/*.svg', { eager: true });

const icon = computed<Component>(() => {
    return icons[`../../../svg/${props.name}.svg`] ?? icons['../../../svg/icons/image.svg'];
});
</script>

<template>
    <component :is="icon" />
</template>
