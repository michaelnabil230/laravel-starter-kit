<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';

defineProps<{
    resource: string;
    click: VoidFunction;
}>();
</script>

<template>
    <Button color="secondary" variant="outlined" @click="click" class="text-xs">
        <SvgIcon name="icons/upload" />
        {{ __('export.resource', { resource: __('resources.' + resource + '.plural') }) }}
    </Button>
</template>
