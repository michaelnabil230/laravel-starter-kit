<script setup lang="ts">
import useLocalization from '@/dashboard/composables/useLocalization';
import { cn } from '@/dashboard/lib/utils';
import { ModelValue, Option } from '@/dashboard/types/option';
import axios from 'axios';
import { debounce } from 'lodash';
import { HSSelect, ISingleOption } from 'preline/preline';
import { computed, onMounted, ref, watch } from 'vue';

const { __ } = useLocalization();

const input = ref<HTMLInputElement | null>(null);

const search = ref<string>('');

const props = withDefaults(
    defineProps<{
        attribute: string;
        endPoint: string;
        hasError?: boolean;
        selected?: Option | Option[] | null;
    }>(),
    {
        hasError: false,
    },
);

const defaultValue = computed(() => {
    if (Array.isArray(props.selected)) {
        return props.selected.map((selected) => selected.value);
    }

    return props.selected?.value;
});

const model = defineModel<ModelValue | null>();

if (model.value === undefined) {
    model.value = defaultValue.value;
}

const originalSearchOptionsMethod = HSSelect.prototype['searchOptions'];

HSSelect.prototype['searchOptions'] = function (value: string) {
    search.value = value;
    const originalSearchOptionsMethodDebounce = debounce(() => originalSearchOptionsMethod.apply(this, [value]), 200);

    originalSearchOptionsMethodDebounce();
};

const searchRequest = async () => {
    try {
        const { data } = await axios.get<Option[]>(props.endPoint, {
            params: { search: search.value },
        });

        const options: ISingleOption[] = data.map(
            (option: Option) =>
                ({
                    title: option.label,
                    val: option.value.toString(),
                }) as ISingleOption,
        );

        const select = HSSelect.getInstance(input.value as HTMLInputElement) as HSSelect;
        select.addOption(options);
    } catch (error) {
        console.error('Error fetching:', error);
    }
};

onMounted(async () => {
    await searchRequest();

    if (props.selected != undefined) {
        const select = HSSelect.getInstance(input.value as HTMLInputElement) as HSSelect;

        if (Array.isArray(props.selected)) {
            props.selected.forEach((selected) => addOptionAndSelect(select, selected));
        } else {
            addOptionAndSelect(select, props.selected);
        }
    }
});

const addOptionAndSelect = (select: HSSelect, option: Option) => {
    select.addOption({
        title: option.label,
        val: option.value,
    });
    select.setValue(option.value);
};

watch(search, () => searchRequest());

const hsSelectOptions = computed(() => {
    const title = Array.isArray(model.value) ? '' : '<span data-title></span>';

    return {
        hasSearch: true,
        searchPlaceholder: __('global.search.name'),
        placeholder: __('global.choose', { attribute: props.attribute }),
        searchNoResultText: __('global.not_found.results'),
        toggleTag: '<button type="button" aria-expanded="false">' + title + '</button>',
        searchClasses:
            'block w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-1 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-2 px-3',
        searchWrapperClasses: 'bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900',
        toggleClasses: cn(
            'hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 px-3 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:focus:outline-hidden dark:focus:ring-1 dark:focus:ring-neutral-600',
            {
                'dark:border-red-700 border-red-500 focus:border-red-500 focus:ring-red-500': props.hasError,
            },
        ),
        dropdownClasses:
            'z-50 mt-2 max-h-72 w-full space-y-0.5 overflow-hidden overflow-y-auto rounded-lg border border-gray-200 bg-white p-1 pt-0 dark:border-neutral-700 dark:bg-neutral-900',
        optionClasses:
            'py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800',
        optionTemplate:
            '<div class="flex justify-between items-center w-full"><div class="hs-selected:font-semibold text-sm text-gray-800 dark:text-neutral-200" data-title></div><span class="hidden hs-selected:block"><svg class="shrink-0 size-3.5 text-blue-700 dark:text-blue-500" xmlns="http:.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span></div>',
        extraMarkup: [
            props.hasError
                ? '<div class="absolute top-1/2 end-8 -translate-y-1/2"><svg class="shrink-0 size-4 text-red-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg></div>'
                : null,
            '<div class="absolute top-1/2 end-3 -translate-y-1/2"><svg class="shrink-0 size-3.5 text-gray-500 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg></div>',
        ].filter((item) => item != null),
    };
});
</script>

<template>
    <select class="hidden" ref="input" v-model="model" :data-hs-select="JSON.stringify(hsSelectOptions)"></select>
</template>
