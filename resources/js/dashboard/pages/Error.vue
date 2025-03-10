<script setup lang="ts">
import ErrorLayout from '@/dashboard/layouts/ErrorLayout.vue';
import { computed } from 'vue';

const props = defineProps<{
    status: number;
}>();

const title = computed(() => {
    return (
        {
            500: 'Server Error',
            503: 'Service Unavailable',
            404: 'Page Not Found',
            403: 'Forbidden',
        }[props.status] || 'Error ' + props.status
    );
});

const description = computed(() => {
    return (
        {
            500: 'Whoops, something went wrong on our servers.',
            503: 'Sorry, we are doing some maintenance. Please check back soon.',
            404: 'Sorry, the page you are looking for could not be found.',
            403: 'Sorry, you are forbidden from accessing this page.',
        }[props.status] || 'An unexpected error occurred.'
    );
});
</script>

<template>
    <ErrorLayout :title="title">
        <h1 class="block text-7xl font-bold text-gray-800 dark:text-white sm:text-9xl">
            {{ props.status }}
        </h1>
        <p class="mt-3 text-gray-600 dark:text-neutral-400">{{ title }}</p>
        <p class="text-gray-600 dark:text-neutral-400">
            {{ description }}
        </p>
    </ErrorLayout>
</template>
