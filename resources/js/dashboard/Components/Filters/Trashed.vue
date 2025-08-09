<script setup lang="ts">
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import Select from '@/dashboard/Components/Inputs/Select/Select.vue';
import { Filter } from '@/dashboard/composables/useFilter';
import useLocalization from '@/dashboard/composables/useLocalization';
import { computed } from 'vue';

const { __ } = useLocalization();

const props = defineProps<{
    filter: Filter;
}>();

const options = computed(() => [
    { value: 'with', label: __('filters.trashed.with') },
    { value: 'only', label: __('filters.trashed.only') },
]);

const trashed = computed({
    get: () => props.filter.queryBuilderData.value.trashed,
    set: (value: string) => props.filter.onTrashedChange(value),
});
</script>

<template>
    <div>
        <InputLabel :value="__('filters.trashed.name')" for="trashed" />
        <Select
            id="trashed"
            :attribute="__('filters.trashed.name')"
            :allowEmpty="true"
            v-model="trashed"
            :options="options"
        />
    </div>
</template>
