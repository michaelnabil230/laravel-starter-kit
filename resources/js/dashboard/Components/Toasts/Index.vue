<script setup lang="ts">
import useToasts from '@/dashboard/composables/useToasts';
import ToastItem from './Toast.vue';

const toasts = useToasts();
</script>

<template>
    <TransitionGroup
        tag="div"
        enter-from-class="rtl:-translate-x-full translate-x-full opacity-0"
        enter-active-class="duration-500"
        leave-active-class="duration-500"
        leave-to-class="rtl:-translate-x-full translate-x-full opacity-0"
        class="fixed end-5 bottom-5 z-80 w-full max-w-xs space-y-3"
    >
        <ToastItem v-for="toast in toasts.items" :key="toast.id" :toast="toast" />
    </TransitionGroup>
</template>
