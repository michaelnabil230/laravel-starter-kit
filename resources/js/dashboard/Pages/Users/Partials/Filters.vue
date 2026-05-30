<script setup lang="ts">
import Trashed from '@/dashboard/Components/Filters/Trashed.vue';
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import Select from '@/dashboard/Components/Inputs/Select.vue';
import { Option } from '@/dashboard/Components/Inputs/types';
import { Modal, ModalDescription, ModalHeader, ModalTitle } from '@/dashboard/Components/Modal/Index';
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
    <Modal type="slideover" name="filter">
        <ModalHeader>
            <ModalTitle>
                {{ __('filters.name') }}
            </ModalTitle>
            <ModalDescription />
        </ModalHeader>

        <div class="flex h-[calc(100%-58px)] flex-col">
            <div class="h-full overflow-hidden overflow-y-auto p-5">
                <div
                    class="mb-5 border-b border-stone-200 pb-5 last:mb-0 last:border-b-0 last:pb-0 dark:border-neutral-700"
                >
                    <Trashed :filter="filter" />
                </div>

                <div class="mt-3">
                    <InputLabel :value="__('global.gender')" for="gender" />
                    <Select
                        :placeholder="__('global.choose', { attribute: __('global.gender') })"
                        v-model="form.gender"
                        :options="genders"
                        id="gender"
                    />
                </div>
            </div>
        </div>
    </Modal>
</template>
