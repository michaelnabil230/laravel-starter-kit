<script setup lang="ts">
import { cn } from '@/dashboard/lib/utils';
import { Option } from '@/dashboard/types/option';
import { debounce, uniqBy } from 'lodash';
import type { SelectRootEmits, SelectRootProps } from 'reka-ui';
import {
    SelectContent,
    SelectIcon,
    SelectPortal,
    SelectRoot,
    SelectTrigger,
    SelectValue,
    SelectViewport,
    useFilter,
    useForwardPropsEmits,
} from 'reka-ui';
import { computed, HTMLAttributes, onMounted, onUnmounted, ref, watch } from 'vue';
import SelectItem from './SelectItem.vue';

interface Props {
    attribute: string;
    hasError?: boolean;
    endPoint?: string;
    options?: Option[];
    selected?: Option | Option[] | null;
    class?: HTMLAttributes['class'];
    classViewPort?: HTMLAttributes['class'];
    hasSearch?: boolean;
    allowEmpty?: boolean;
}

const props = withDefaults(defineProps<SelectRootProps & Props>(), {
    hasError: false,
    options: () => [] as Option[],
    hasSearch: true,
    allowEmpty: false,
});

defineSlots<{
    default(props: { options: Option[] }): void;
}>();

const emits = defineEmits<SelectRootEmits>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { attribute, hasError, endPoint, options, selected, ...delegated } = props;

    return delegated;
});

const forwarded = useForwardPropsEmits(delegatedProps, emits);

const defaultOptions = computed(() => {
    const options = [];

    if (props.allowEmpty) {
        options.push({ value: null, label: 'N/A' });
    }

    if (props.selected) {
        if (Array.isArray(props.selected)) {
            options.push(...props.selected);
        } else {
            options.push(props.selected);
        }
    }

    return options;
});

const optionsRef = ref([...defaultOptions.value, ...props.options]);

const search = ref('');

const { contains } = useFilter({ sensitivity: 'base' });

const filteredOptions = computed(() => {
    if (props.endPoint) {
        return optionsRef.value;
    }

    return optionsRef.value.filter((option) => contains(option.value ?? '', search.value));
});

if (props.endPoint) {
    const debouncedSearch = debounce(() => searchRequest(), 300);

    onMounted(() => searchRequest());

    watch(search, () => debouncedSearch());

    onUnmounted(() => debouncedSearch.cancel());

    const searchRequest = async () => {
        try {
            const { data } = await axios.get<Option[]>(props.endPoint!, {
                params: { search: search.value },
            });

            optionsRef.value = uniqBy([...defaultOptions.value, ...data], 'value');
        } catch (error) {
            console.error('Error fetching:', error);
        }
    };
}
</script>

<template>
    <SelectRoot v-bind="forwarded">
        <SelectTrigger
            :class="
                cn(
                    'py-2  px-3 flex justify-between items-center gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:focus:outline-hidden dark:focus:ring-1 dark:focus:ring-neutral-600 disabled:pointer-events-none disabled:opacity-50',
                    {
                        'focus:border-red-500 focus:ring-red-500 border-red-500 dark:focus:border-red-500 dark:focus:ring-red-500 dark:border-red-500':
                            hasError,
                    },
                    props.class,
                )
            "
        >
            <SelectValue :placeholder="__('global.choose', { attribute: attribute })" class="truncate" />
            <SelectIcon class="flex items-center justify-center gap-2">
                <svg
                    v-if="hasError"
                    class="size-3.5 shrink-0 text-red-500"
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
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" x2="12" y1="8" y2="12"></line>
                    <line x1="12" x2="12.01" y1="16" y2="16"></line>
                </svg>
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
            </SelectIcon>
        </SelectTrigger>

        <SelectPortal>
            <SelectContent
                position="popper"
                class="max-h-72 z-100 space-y-0.5 overflow-hidden overflow-y-auto rounded-lg border border-gray-200 bg-white p-1 dark:border-neutral-700 dark:bg-neutral-900 data-[side=bottom]:translate-y-1 data-[side=left]:-translate-x-1 data-[side=right]:translate-x-1 data-[side=top]:-translate-y-1 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2"
            >
                <div v-if="hasSearch" class="bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900">
                    <input
                        v-model="search"
                        :placeholder="__('global.search.name')"
                        type="text"
                        class="block w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-2 px-3"
                    />
                </div>

                <SelectViewport :class="cn('', classViewPort)">
                    <span
                        v-if="filteredOptions.length === 0"
                        class="py-2 inline-flex w-full px-4 text-sm text-gray-800 dark:text-neutral-200"
                    >
                        {{ __('global.not_found.results') }}
                    </span>

                    <template v-else>
                        <template v-if="$slots.default == null">
                            <SelectItem v-for="(option, index) in filteredOptions" :key="index" :value="option.value">
                                {{ option.label }}
                            </SelectItem>
                        </template>

                        <slot v-else :options="options" />
                    </template>
                </SelectViewport>
            </SelectContent>
        </SelectPortal>
    </SelectRoot>
</template>
