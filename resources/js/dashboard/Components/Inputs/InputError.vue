<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';

defineProps<{
    message?: string | null;
}>();
</script>

<template>
    <p v-if="message" class="mt-2 inline-flex items-center gap-1 text-sm text-red-600">
        <SvgIcon name="icons/danger" class="size-4" />
        {{ message }}
    </p>
</template>
