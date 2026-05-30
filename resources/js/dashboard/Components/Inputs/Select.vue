<script setup lang="ts">
import { Option, SelectProps } from '@/dashboard/Components/Inputs/types';
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
import Loading from '@/dashboard/Components/Loading.vue';
import { cn } from '@/dashboard/lib/utils';
import { useHttp } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { computed, nextTick, onMounted, onUnmounted, ref, useAttrs, watch } from 'vue';
import { visitModal } from '@/dashboard/composables/modal/modalStack.js';
import {
    ComboboxAnchor,
    ComboboxContent,
    ComboboxEmpty,
    ComboboxInput,
    ComboboxItem,
    ComboboxItemIndicator,
    ComboboxPortal,
    ComboboxRoot,
    ComboboxTrigger,
    ComboboxViewport,
    useFilter,
} from 'reka-ui';

const props = withDefaults(defineProps<SelectProps>(), {
    modelValue: null,
    options: () => [],
    endPoint: null,
    initialValue: null,
    debounceMs: 300,
    disabled: false,
    hasError: false,
    multiple: false,
    searchable: true,
    clearable: false,
    ignoreFilter: false,
    creatable: false,
    modalName: '',
});

defineOptions({
    inheritAttrs: false,
});

const attrs = useAttrs();

const { contains } = useFilter({ sensitivity: 'base' });

watch(
    () => props.initialValue,
    (newValue) => {
        if (newValue) {
            const exists = internalOptions.value.some((item) => item.value === newValue.value);
            if (!exists) {
                internalOptions.value.push(newValue);
            }
            updateModelValue(newValue.value);
        }
    },
    { immediate: true },
);

onMounted(() => {
    if (props.endPoint) {
        fetchOptions('');
    }
});

onUnmounted(() => {
    debouncedFetch.cancel();
});

const emit = defineEmits(['update:modelValue', 'search', 'selected', 'added', 'created']);

const search = ref('');
const open = ref(false);
const internalOptions = ref<Option[]>([]);
const rootRef = ref();
const searchInputRef = ref();

const shouldShowInput = computed(() => {
    if (!props.searchable) return false;

    const hasValue = props.multiple
        ? Array.isArray(props.modelValue) && props.modelValue.length > 0
        : !!props.modelValue;

    return open.value || !hasValue;
});

const selectedLabel = computed(() => {
    return selectedOptions.value
        .map((value: any) => {
            const option = displayedOptions.value.find((item) => item.value === value);

            return option?.label ?? value;
        })
        .filter(Boolean)
        .join(', ');
});

const displayedOptions = computed(() => {
    if (props.endPoint) {
        return internalOptions.value;
    }

    return props.options.filter(
        (option) => contains(option.value.toString(), search.value) || contains(option.label.toString(), search.value),
    );
});

const canClearSelection = computed(() => props.clearable && props.modelValue);

const showCreateOption = computed(() => {
    if (!props.creatable || !search.value.trim() || displayedOptions.value.length > 0) return false;

    return !displayedOptions.value.some(
        (option) => contains(option.label.toString(), search.value) || contains(option.value.toString(), search.value),
    );
});

const handleCreate = () => {
    if (props.modalName) {
        visitModal('#' + props.modalName);
    }
    search.value = '';
    open.value = false;
};

const onCreatedItem = (item: Option) => {
    const exists = internalOptions.value.some((opt) => opt.value === item.value);
    if (!exists) {
        internalOptions.value.push(item);
    }

    const value = props.multiple
        ? Array.isArray(props.modelValue)
            ? [...props.modelValue, item.value]
            : [item.value]
        : item.value;
    updateModelValue(value);
    emit('created', item);
};

const shouldShowOptionsChevron = computed(() => displayedOptions.value.length > 0 || props.ignoreFilter);

const wrapperAttrs = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: __, ...delegated } = attrs;

    return delegated;
});

const http = useHttp<{ search: string }, Option[]>({
    search: '',
});

const fetchOptions = (query: string) => {
    if (!props.endPoint) return;

    http.search = query;

    http.get(props.endPoint, {
        onSuccess: (data) => {
            internalOptions.value = Array.isArray(data) ? data : [];
        },
    });
};

const debouncedFetch = debounce(fetchOptions, props.debounceMs);

watch(search, debouncedFetch);

const selectedOptions = computed<(string | number)[]>(() => {
    if (props.modelValue === null || props.modelValue === undefined) {
        return [];
    }

    return Array.isArray(props.modelValue) ? props.modelValue : [props.modelValue];
});

const updateModelValue = (value: any) => {
    search.value = '';

    emit('update:modelValue', value);

    const newValues = Array.isArray(value) ? value : [value];

    const oldValues =
        props.modelValue == null ? [] : Array.isArray(props.modelValue) ? props.modelValue : [props.modelValue];

    newValues
        .filter((option) => !oldValues.includes(option))
        .forEach((option) => {
            emit('selected', option);
        });
};

const onBlur = (e: FocusEvent) => {
    const relatedTarget = e.relatedTarget as HTMLElement;

    const isInsideDropdown = relatedTarget?.dataset && 'rekaCollectionItem' in relatedTarget.dataset;

    if (isInsideDropdown) return;

    pushTaggableOption(e);
};

const pushTaggableOption = (e: any) => {
    if (!props.multiple) return;

    const value = e?.target?.value?.trim();

    if (!value) return;

    e.preventDefault();

    const existingValues = Array.isArray(props.modelValue) ? props.modelValue : [];

    const alreadyExists = existingValues.some((item: any) => {
        if (typeof item === 'object') {
            return item?.value === value;
        }

        return item === value;
    });

    if (alreadyExists) {
        search.value = '';
        return;
    }

    emit('added', value);

    updateModelValue([...existingValues, value]);

    search.value = '';
};

const updateDropdownOpen = (value: boolean) => {
    if (props.disabled) return;

    open.value = value;

    if (value) {
        focus();
        requestAnimationFrame(() => {
            scrollToSelectedOption();
        });
    }
};

const openDropdown = (e: KeyboardEvent) => {
    if (open.value) return;

    if (e.key === ' ' && (e.target as HTMLElement)?.tagName === 'INPUT') {
        return;
    }

    e.preventDefault();

    updateDropdownOpen(true);
};

const scrollToSelectedOption = () => {
    if (props.multiple || !props.modelValue) {
        return;
    }

    rootRef.value?.highlightSelected?.();
};

const focus = () => {
    nextTick(() => {
        if (shouldShowInput.value) {
            searchInputRef.value?.$el.focus();
        } else {
            searchInputRef.value?.$el?.focus?.();
        }
    });
};

const clear = () => {
    search.value = '';
    emit('update:modelValue', null);
};

defineExpose({ onCreatedItem });
</script>

<template>
    <div :class="cn('w-full min-w-0', attrs.class)" v-bind="wrapperAttrs">
        <div class="flex w-full min-w-0">
            <ComboboxRoot
                ref="rootRef"
                class="min-w-0 flex-1 cursor-pointer"
                :multiple="multiple"
                :open="open"
                :model-value="modelValue"
                :disabled="disabled"
                :ignore-filter="ignoreFilter"
                :reset-search-term-on-blur="false"
                :reset-search-term-on-select="false"
                @update:open="updateDropdownOpen"
                @update:model-value="updateModelValue"
            >
                <ComboboxAnchor class="block w-full" data-ui-combobox-anchor>
                    <ComboboxTrigger
                        as="div"
                        :class="
                            cn(
                                'flex w-full cursor-pointer items-center justify-between gap-x-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-start text-sm text-nowrap focus:ring-2 focus:ring-blue-500 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-transparent dark:text-neutral-300 dark:focus:ring-1 dark:focus:ring-neutral-600 dark:focus:outline-hidden',
                                {
                                    'border-red-500 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:focus:border-red-500 dark:focus:ring-red-500':
                                        props.hasError,
                                },
                            )
                        "
                        ref="trigger"
                        data-ui-combobox-trigger
                        @keydown.enter="openDropdown"
                        @keydown.space="openDropdown"
                    >
                        <div class="min-w-0 flex-1">
                            <ComboboxInput
                                v-if="shouldShowInput"
                                ref="searchInputRef"
                                v-model="search"
                                :placeholder="placeholder"
                                type="text"
                                autocomplete="off"
                                class="w-full cursor-pointer border-0 bg-transparent p-0 text-sm opacity-100 ring-0 outline-none placeholder:text-gray-400 focus:border-0 focus:bg-transparent focus:ring-0 focus:outline-none focus-visible:border-0 focus-visible:bg-transparent focus-visible:ring-0 focus-visible:outline-none active:ring-0 active:outline-none dark:text-neutral-300 dark:placeholder:text-white/60"
                                @blur="onBlur"
                                @keydown.enter="pushTaggableOption"
                            />

                            <div
                                v-else
                                class="flex w-full cursor-pointer items-center gap-2 bg-transparent text-start select-none focus:outline-none"
                                data-ui-combobox-selected-option
                            >
                                <span v-if="selectedOptions.length > 0" v-text="selectedLabel" class="block truncate" />
                                <span
                                    v-else
                                    class="block truncate text-gray-500 dark:text-gray-400"
                                    v-text="placeholder"
                                />
                            </div>
                        </div>

                        <div
                            v-if="canClearSelection || shouldShowOptionsChevron"
                            class="ms-1.5 -me-1 flex items-center gap-1.5"
                        >
                            <SvgIcon
                                v-if="canClearSelection"
                                data-ui-combobox-clear-button
                                name="icons/close"
                                class="size-3.5 shrink-0 text-gray-500 dark:text-neutral-500"
                                @click="clear"
                            />
                            <SvgIcon
                                v-if="shouldShowOptionsChevron"
                                data-ui-combobox-chevron
                                name="icons/unfold-vertical"
                                class="size-3.5 shrink-0 text-gray-500 dark:text-neutral-500"
                            />
                            <SvgIcon
                                v-if="props.hasError"
                                name="icons/warning"
                                class="size-3.5 shrink-0 text-red-500"
                            />
                        </div>
                    </ComboboxTrigger>
                </ComboboxAnchor>

                <ComboboxPortal>
                    <ComboboxContent
                        :side-offset="5"
                        position="popper"
                        :class="[
                            'z-100 inline-flex w-max max-w-md overflow-hidden rounded-lg bg-white p-2 text-sm text-gray-800 dark:bg-neutral-900 dark:text-neutral-200',
                            'max-h-(--reka-combobox-content-available-height) min-w-(--reka-combobox-trigger-width)',
                        ]"
                        data-ui-combobox-content
                        @escape-key-down="focus"
                    >
                        <ComboboxEmpty class="text-sm text-gray-600 dark:text-neutral-300">
                            <p v-if="showCreateOption" class="leading-relaxed">
                                {{ __('global.not_found.results') }}

                                <span class="text-gray-500 dark:text-neutral-400">
                                    — {{ __('create_new_record') }}
                                </span>

                                <button
                                    type="button"
                                    class="ml-1 cursor-pointer font-medium text-blue-600 underline underline-offset-2 transition hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
                                    @mousedown.prevent="handleCreate"
                                >
                                    {{ __('global.crud.create') }}
                                </button>
                            </p>

                            <span v-else>
                                {{ __('global.not_found.results') }}
                            </span>
                        </ComboboxEmpty>

                        <ComboboxViewport class="max-h-60 w-full space-y-1 overflow-auto">
                            <ComboboxItem
                                v-for="option in displayedOptions"
                                :key="option.value"
                                :value="option.value"
                                :disabled="option.disabled"
                                class="flex w-full cursor-pointer items-center justify-between rounded-lg p-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden data-[state=checked]:font-semibold dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                            >
                                <span class="truncate">
                                    {{ option.label }}
                                </span>

                                <ComboboxItemIndicator>
                                    <SvgIcon name="icons/check" class="size-4 text-blue-600" />
                                </ComboboxItemIndicator>
                            </ComboboxItem>
                        </ComboboxViewport>

                        <Loading v-if="http.processing && displayedOptions.length > 0" />
                    </ComboboxContent>
                </ComboboxPortal>
            </ComboboxRoot>
        </div>

        <slot name="modal" @created="onCreatedItem" />
    </div>
</template>
