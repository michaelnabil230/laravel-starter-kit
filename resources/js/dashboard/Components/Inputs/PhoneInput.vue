<script setup lang="ts">
import useLocalization from '@/dashboard/composables/useLocalization';
import countries from '@/utilities/countries.json';
import { computed, onMounted, ref } from 'vue';
import TextInput from './TextInput.vue';

const phoneModel = defineModel<string>('phone', { required: true });

const phoneCountryModel = defineModel<string>('phone_country', { required: true });

const input = ref<HTMLInputElement | null>(null);

withDefaults(
    defineProps<{
        hasError?: boolean;
    }>(),
    {
        hasError: false,
    },
);

defineOptions({
    inheritAttrs: false,
});

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value?.focus();
    }
});

defineExpose({ focus: () => input.value?.focus() });

const { __ } = useLocalization();

const hsSelectOptions = computed(() => {
    return {
        hasSearch: true,
        searchPlaceholder: __('global.search.name'),
        placeholder: __('global.choose', { attribute: 'phone' }),
        searchNoResultText: __('global.not_found.results'),
        toggleTag: '<button type="button" aria-expanded="false"></button>',
        searchClasses:
            'block w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-[1] dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-2 px-3',
        searchWrapperClasses: 'bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900',
        toggleClasses:
            'border-e-none rounded-e-none hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 px-3 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600',
        dropdownClasses:
            'z-50 mt-2 max-h-72 w-full space-y-0.5 overflow-hidden overflow-y-auto rounded-lg border border-gray-200 bg-white p-1 pt-0 dark:border-neutral-700 dark:bg-neutral-900',
        optionClasses:
            'py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800',
        optionTemplate:
            '<div class="flex justify-between items-center w-full"><div class="hs-selected:font-semibold text-sm text-gray-800 dark:text-neutral-200" data-title></div><span class="hidden hs-selected:block"><svg class="shrink-0 size-3.5 text-blue-700 dark:text-blue-500" xmlns="http:.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span></div>',
        extraMarkup:
            '<div class="absolute top-1/2 end-3 -translate-y-1/2"><svg class="shrink-0 size-3.5 text-gray-500 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg></div>',
    };
});
</script>

<!-- TODO: Complete the code -->
<template>
    <div class="flex rounded-lg">
        <div>
            <label for="select-country" class="sr-only"> Country </label>
            <select
                id="select-country"
                v-model="phoneCountryModel"
                :data-hs-select="JSON.stringify(hsSelectOptions)"
                class="hidden"
            >
                <option v-for="country in countries" :value="country['code']" :key="country['code']">
                    {{ country['name']['ar'] }}
                </option>
            </select>
        </div>
        <TextInput
            type="number"
            class="rounded-s-none"
            v-model="phoneModel"
            :hasError="hasError"
            :fullWidth="true"
            :placeholder="
                __('global.placeholder', {
                    attribute: __('global.attributes.phone'),
                })
            "
        />
    </div>
</template>
