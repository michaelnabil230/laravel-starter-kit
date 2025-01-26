<script setup lang="ts">
defineProps<{
    message?: string;
}>();
</script>

<template>
    <p v-show="message" class="mt-2 text-sm text-red-600">
        {{ message }}
    </p>
</template>
