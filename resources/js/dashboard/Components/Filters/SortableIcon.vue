<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
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
    <button type="button" @click.prevent="handleClick" class="inline-flex cursor-pointer items-center">
        <slot />

        <SvgIcon
            v-if="isSorted && isDescDirection"
            name="icons/arrow-down"
            class="group ms-1 size-3.5"
            :class="'text-blue-600 dark:text-blue-500'"
        />
        <SvgIcon
            v-else-if="isSorted && !isDescDirection"
            name="icons/arrow-up"
            class="group ms-1 size-3.5"
            :class="'text-blue-600 dark:text-blue-500'"
        />
        <SvgIcon v-else name="icons/arrow-down" class="group ms-1 size-3.5 text-gray-400 dark:text-neutral-500" />
    </button>
</template>
