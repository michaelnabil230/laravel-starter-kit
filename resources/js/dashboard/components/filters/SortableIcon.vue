<script setup lang="ts">
import { Filter } from '@/dashboard/composables/useFilter';
import { computed } from 'vue';

const props = defineProps<{
    column: string;
    filter: Filter;
}>();

const queryBuilderData = props.filter.queryBuilderData;

const isSorted = computed(() => {
    return (
        queryBuilderData.value.order_by === props.column &&
        ['asc', 'desc'].includes(queryBuilderData.value.order_by_direction ?? '')
    );
});

const isDescDirection = computed(() => queryBuilderData.value.order_by_direction === 'desc');

const handleClick = () => {
    if (isSorted.value && isDescDirection.value) {
        props.filter.resetOrderBy();
    } else {
        props.filter.orderByField(props.column);
    }
};
</script>

<template>
    <button type="button" @click.prevent="handleClick" class="cursor-pointer inline-flex items-center">
        <slot />

        <svg
            class="size-3.5 group ms-1 text-gray-400 dark:text-neutral-500"
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
            <path
                :class="{
                    'text-blue-600 dark:text-blue-500': isSorted && isDescDirection,
                }"
                d="m7 15 5 5 5-5"
            ></path>
            <path
                :class="{
                    'text-blue-600 dark:text-blue-500': isSorted && !isDescDirection,
                }"
                d="m7 9 5-5 5 5"
            ></path>
        </svg>
    </button>
</template>
