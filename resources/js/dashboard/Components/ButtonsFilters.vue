<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import { Filter } from '@/dashboard/composables/useFilter';

defineProps<{
    filter: Filter;
}>();
</script>

<template>
    <div class="flex items-center justify-end -space-x-px">
        <Button
            @click="filter.openModal"
            color="secondary"
            variant="outlined"
            class="rounded-none text-xs first:rounded-s-lg last:rounded-e-lg"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <line x1="21" x2="14" y1="4" y2="4" />
                <line x1="10" x2="3" y1="4" y2="4" />
                <line x1="21" x2="12" y1="12" y2="12" />
                <line x1="8" x2="3" y1="12" y2="12" />
                <line x1="21" x2="16" y1="20" y2="20" />
                <line x1="12" x2="3" y1="20" y2="20" />
                <line x1="14" x2="14" y1="2" y2="6" />
                <line x1="8" x2="8" y1="10" y2="14" />
                <line x1="16" x2="16" y1="18" y2="22" />
            </svg>
            {{ __('filters.name') }}
        </Button>
        <Button
            color="secondary"
            variant="outlined"
            v-if="filter.canBeReset.value"
            @click="filter.resetQuery"
            class="rounded-none text-xs first:rounded-s-lg last:rounded-e-lg"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
            {{ __('filters.reset') }}
        </Button>
    </div>
</template>
