<script setup lang="ts">
import Trashed from '@/dashboard/Components/Filters/Trashed.vue';
import Select from '@/dashboard/Components/Inputs/Select/Select.vue';
import { Option } from '@/dashboard/Components/Inputs/types';
import Sheet from '@/dashboard/Components/Sheet/Sheet.vue';
import SheetDescription from '@/dashboard/Components/Sheet/SheetDescription.vue';
import SheetHeader from '@/dashboard/Components/Sheet/SheetHeader.vue';
import SheetTitle from '@/dashboard/Components/Sheet/SheetTitle.vue';
import { Filter } from '@/dashboard/composables/useFilter';
import useLocalization from '@/dashboard/composables/useLocalization';
import { App } from '@/dashboard/types/app';
import { computed } from 'vue';

const { __ } = useLocalization();

const props = defineProps<{
    filter: Filter;
}>();

const form = props.filter.queryBuilderData;

const genders = computed(() => {
    return Object.values(App.Enums.Gender).map((gender) => {
        return {
            label: __('global.enums.gender.' + gender),
            value: gender,
        } as Option;
    });
});
</script>

<template>
    <Sheet :model-value="filter.openFilter.value" @closed="filter.closeModal">
        <!-- Header -->
        <SheetHeader>
            <SheetTitle>
                {{ __('filters.name') }}
            </SheetTitle>
            <SheetDescription />
        </SheetHeader>
        <!-- End Header -->

        <!-- Content -->
        <div class="flex h-[calc(100%-58px)] flex-col">
            <!-- Body -->
            <div class="h-full overflow-hidden overflow-y-auto p-5">
                <div
                    class="mb-5 border-b border-stone-200 pb-5 last:mb-0 last:border-b-0 last:pb-0 dark:border-neutral-700"
                >
                    <Trashed :filter="filter" />
                </div>

                <div>
                    <InputLabel :value="__('global.gender')" for="gender" />
                    <Select :attribute="__('global.gender')" v-model="form.gender" :options="genders" id="gender" />
                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Content -->
    </Sheet>
</template>
