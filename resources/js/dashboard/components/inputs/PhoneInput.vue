<script setup lang="ts">
import useLocalization from '@/dashboard/composables/useLocalization';
import useLocalStorage from '@/dashboard/composables/useLocalStorage';
import parsePhoneNumberFromString, {
    CountryCallingCode,
    CountryCode,
    getCountries,
    getCountryCallingCode,
    isSupportedCountry,
} from 'libphonenumber-js';
import {
    ComboboxEmpty,
    ComboboxInput,
    ComboboxItem,
    ComboboxRoot,
    ComboboxViewport,
    PopoverContent,
    PopoverPortal,
    PopoverRoot,
    PopoverTrigger,
} from 'reka-ui';
import { computed, onMounted, ref } from 'vue';
import Flag from '../Flag.vue';
import TextInput from './TextInput.vue';

interface Country {
    iso2: CountryCode;
    dialCode: CountryCallingCode;
    name: string;
}

defineOptions({ inheritAttrs: false });

const { __ } = useLocalization();

const props = withDefaults(
    defineProps<{
        defaultPhoneValue?: string;
        defaultPhoneCountryValue?: string;
        hasError?: boolean;
        countryCode?: CountryCode;
    }>(),
    {
        hasError: false,
    },
);

const phoneModel = defineModel<string>('phone');

if (phoneModel.value === undefined) {
    phoneModel.value = props.defaultPhoneValue;
}

const phoneCountryModel = defineModel<string>('phone_country', { default: '' });

if (phoneCountryModel.value === undefined) {
    phoneCountryModel.value = props.defaultPhoneCountryValue ?? '';
}

const open = ref(false);

onMounted(async () => {
    if (props.countryCode) {
        setSelectedCountry(props.countryCode);
    }

    if (!phoneCountryModel.value) {
        const country = useLocalStorage<CountryCode | null>('country', null);

        if (country.value.value) {
            setSelectedCountry(country.value.value);
        } else {
            const countryCode = (await fetchCountryCode()) ?? 'EG';
            setSelectedCountry(countryCode);
            country.value.value = countryCode;
        }
    }

    if (phoneModel.value !== '') {
        const results = parsePhoneNumberFromString(phoneModel.value as string, phoneCountryModel.value as CountryCode);

        if (results !== undefined) {
            phoneModel.value = results.nationalNumber;
        }
    }
});

const setSelectedCountry = (countryCode: CountryCode) => {
    if (!isSupportedCountry(countryCode)) {
        phoneCountryModel.value = '';
        return;
    }

    phoneCountryModel.value = countryCode;
};

const fetchCountryCode = async () => {
    try {
        const response = await fetch('https://ipwho.is');

        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const data = await response.json();

        return data.country_code;
    } catch {
        return null;
    }
};

const countries = computed(() => {
    const countries: Country[] = [];

    const isoList = getCountries();

    const displayNamesInstance = new Intl.DisplayNames(dashboardApp.config('locale'), { type: 'region' });

    for (const iso2 of isoList) {
        const name = displayNamesInstance.of(iso2);

        if (name) {
            countries.push({
                iso2,
                dialCode: getCountryCallingCode(iso2),
                name,
            });
        }
    }

    return countries;
});

const updateInputValue = (countryCode: CountryCode) => {
    setSelectedCountry(countryCode);
    open.value = false;
};
</script>

<template>
    <div class="flex">
        <PopoverRoot v-model:open="open">
            <PopoverTrigger>
                <div
                    class="inline-flex justify-center items-center gap-1 border dark:border-neutral-700 border-gray-200 border-e-0 rounded-e-none rounded-s-lg px-3 py-2"
                >
                    <svg
                        class="shrink-0 size-3.5 text-gray-500 dark:text-neutral-500"
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
                        <path d="m7 15 5 5 5-5" />
                        <path d="m7 9 5-5 5 5" />
                    </svg>
                    <Flag :country="phoneCountryModel" />
                    <span v-if="phoneCountryModel" class="text-sm text-gray-800 dark:text-neutral-200">
                        +{{ getCountryCallingCode(phoneCountryModel as CountryCode) }}
                    </span>
                </div>
            </PopoverTrigger>
            <PopoverPortal>
                <PopoverContent
                    class="max-h-72 w-70 absolute space-y-0.5 overflow-hidden overflow-y-auto rounded-lg border border-gray-200 bg-white dark:border-neutral-700 dark:bg-neutral-900 data-[side=bottom]:translate-y-1 data-[side=left]:-translate-x-1 data-[side=right]:translate-x-1 data-[side=top]:-translate-y-1 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2"
                >
                    <ComboboxRoot v-model:open="open">
                        <div class="bg-white p-2 z-100 sticky top-0 dark:bg-neutral-900">
                            <ComboboxInput
                                :placeholder="__('global.search.name')"
                                class="block w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-2 px-3"
                            />
                        </div>
                        <div class="px-1">
                            <ComboboxEmpty
                                class="py-2 inline-flex w-full px-4 text-sm text-gray-800 dark:text-neutral-200"
                            >
                                {{ __('global.not_found.results') }}
                            </ComboboxEmpty>
                            <ComboboxViewport>
                                <ComboboxItem
                                    v-for="option in countries"
                                    :key="option.iso2"
                                    :value="option.name"
                                    class="cursor-pointer gap-2 py-2 px-4 w-full flex justify-between items-center text-sm text-gray-800 hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800"
                                    @select="() => updateInputValue(option.iso2)"
                                >
                                    <Flag :country="option.iso2" />
                                    <span class="flex-1">{{ option.name }}</span>
                                    <span>+{{ option.dialCode }}</span>
                                </ComboboxItem>
                            </ComboboxViewport>
                        </div>
                    </ComboboxRoot>
                </PopoverContent>
            </PopoverPortal>
        </PopoverRoot>
        <div class="w-full">
            <TextInput
                class="rounded-s-none spin-button-none"
                type="number"
                v-model="phoneModel"
                :hasError="hasError"
                :placeholder="
                    __('global.placeholder', {
                        attribute: __('global.attributes.phone'),
                    })
                "
                v-bind="$attrs"
            />
        </div>
    </div>
</template>
