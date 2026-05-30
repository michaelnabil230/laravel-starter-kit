<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
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
            <SvgIcon name="icons/filter" />
            {{ __('filters.name') }}
        </Button>
        <Button
            color="secondary"
            variant="outlined"
            v-if="filter.canBeReset.value"
            @click="filter.resetQuery"
            class="rounded-none text-xs first:rounded-s-lg last:rounded-e-lg"
        >
            <SvgIcon name="icons/close" />
            {{ __('filters.reset') }}
        </Button>
    </div>
</template>
