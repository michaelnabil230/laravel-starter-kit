<script setup lang="ts">
import useInitialSharedData from '@/dashboard/composables/useInitialSharedData';
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
    useForwardProps,
} from 'reka-ui';
import { computed, onMounted, ref } from 'vue';
import Flag from '../Flag.vue';
import TextInput from './TextInput.vue';
import { type PhoneInputProps } from './types';

interface Country {
    iso2: CountryCode;
    dialCode: CountryCallingCode;
    name: string;
}

defineOptions({ inheritAttrs: false });

const initialSharedData = useInitialSharedData();
const { __ } = useLocalization();

const props = withDefaults(defineProps<PhoneInputProps>(), {
    hasError: false,
});

const forwardedProps = useForwardProps(props);

const phoneModel = defineModel<string>('phone');

if (phoneModel.value === undefined) {
    phoneModel.value = props.defaultPhoneValue ?? '';
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

    const displayNamesInstance = new Intl.DisplayNames(initialSharedData('locale'), { type: 'region' });

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
                    class="inline-flex items-center justify-center gap-1 rounded-s-lg rounded-e-none border border-e-0 border-gray-200 px-3 py-2 dark:border-neutral-700"
                >
                    <svg
                        class="size-3.5 shrink-0 text-gray-500 dark:text-neutral-500"
                        xmlns="http://www.w3.org/2000/svg"
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
                    class="data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 absolute max-h-72 w-70 space-y-0.5 overflow-hidden overflow-y-auto rounded-lg border border-gray-200 bg-white data-[side=bottom]:translate-y-1 data-[side=left]:-translate-x-1 data-[side=right]:translate-x-1 data-[side=top]:-translate-y-1 dark:border-neutral-700 dark:bg-neutral-900"
                >
                    <ComboboxRoot v-model:open="open">
                        <div class="sticky top-0 z-100 bg-white p-2 dark:bg-neutral-900">
                            <ComboboxInput
                                :placeholder="__('global.search.name')"
                                class="block w-full rounded-lg border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-400 dark:placeholder-neutral-500"
                            />
                        </div>
                        <div class="px-1">
                            <ComboboxEmpty
                                class="inline-flex w-full px-4 py-2 text-sm text-gray-800 dark:text-neutral-200"
                            >
                                {{ __('global.not_found.results') }}
                            </ComboboxEmpty>
                            <ComboboxViewport>
                                <ComboboxItem
                                    v-for="option in countries"
                                    :key="option.iso2"
                                    :value="option.name"
                                    class="flex w-full cursor-pointer items-center justify-between gap-2 rounded-lg px-4 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
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
                class="spin-button-none rounded-s-none"
                type="number"
                v-model="phoneModel"
                v-bind="forwardedProps"
            />
        </div>
    </div>
</template>
