<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
import { CollapsibleContent, CollapsibleRoot, CollapsibleTrigger } from 'reka-ui';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        value: any;
        depth?: number;
    }>(),
    {
        depth: 0,
    },
);

const MAX_DEPTH = 5;

const isObject = (val: any): boolean => {
    return val !== null && typeof val === 'object' && !Array.isArray(val) && !(val instanceof Date);
};

const isArray = (val: any): boolean => {
    return Array.isArray(val);
};

const isDate = (val: any): boolean => {
    return val instanceof Date;
};

const formatPrimitive = (val: any): string => {
    if (val === null) return 'null';
    if (val === undefined) return 'undefined';
    if (typeof val === 'boolean') return val ? 'true' : 'false';
    if (typeof val === 'string') return val;
    if (typeof val === 'number') return String(val);
    if (isDate(val)) return val.toLocaleString();
    return String(val);
};

const shouldExpand = computed(() => props.depth < MAX_DEPTH);

const displayValue = computed(() => {
    if (props.value === null || props.value === undefined) {
        return { type: 'null', formatted: 'null' };
    }
    if (isDate(props.value)) {
        return { type: 'date', formatted: props.value.toLocaleString() };
    }
    if (isArray(props.value)) {
        return { type: 'array', formatted: `Array(${props.value.length})` };
    }
    if (isObject(props.value)) {
        const keys = Object.keys(props.value);
        return { type: 'object', formatted: `Object(${keys.length})` };
    }
    return { type: 'primitive', formatted: formatPrimitive(props.value) };
});
</script>

<template>
    <div :class="['text-sm', { 'mt-1 ml-2 border-l-2 border-gray-200 pl-2 dark:border-neutral-700': depth > 0 }]">
        <span
            v-if="displayValue.type === 'primitive' || displayValue.type === 'null'"
            class="inline text-gray-700 dark:text-neutral-300"
        >
            {{ displayValue.formatted }}
        </span>

        <div v-else-if="displayValue.type === 'date'" class="inline">
            <span class="text-blue-600 dark:text-blue-400">{{ displayValue.formatted }}</span>
        </div>

        <CollapsibleRoot v-else-if="displayValue.type === 'array'" :defaultOpen="shouldExpand" class="w-full">
            <CollapsibleTrigger
                class="group flex w-full cursor-pointer items-center gap-2 py-1 text-start transition-colors hover:text-gray-900 focus:outline-hidden dark:hover:text-neutral-100"
            >
                <SvgIcon
                    name="icons/chevron-down"
                    class="size-3 shrink-0 text-gray-500 transition-transform group-data-[state=open]:rotate-180 dark:text-neutral-400"
                />
                <span class="text-xs font-medium text-gray-600 dark:text-neutral-400"> Array({{ value.length }}) </span>
            </CollapsibleTrigger>

            <CollapsibleContent
                class="data-[state=closed]:animate-collapsible-up data-[state=open]:animate-collapsible-down overflow-hidden"
            >
                <div v-if="value.length > 0" class="mt-1 space-y-1 ps-4">
                    <div
                        v-for="(item, index) in value"
                        :key="index"
                        class="flex items-start gap-2 py-0.5"
                        :style="{ paddingLeft: `${(props.depth + 1) * 12}px` }"
                    >
                        <span class="min-w-15 flex-0 text-xs font-medium text-gray-500 dark:text-neutral-500">
                            [{{ index }}]
                        </span>
                        <ObjectValue :value="item" :depth="props.depth + 1" />
                    </div>
                </div>
                <div v-else class="py-1 ps-4 text-xs text-gray-400 italic dark:text-neutral-600">
                    {{ __('global.not_found.results') }}
                </div>
            </CollapsibleContent>
        </CollapsibleRoot>

        <CollapsibleRoot v-else-if="displayValue.type === 'object'" :defaultOpen="shouldExpand" class="w-full">
            <CollapsibleTrigger
                class="group flex w-full cursor-pointer items-center gap-2 py-1 text-start transition-colors hover:text-gray-900 focus:outline-hidden dark:hover:text-neutral-100"
            >
                <SvgIcon
                    name="icons/chevron-down"
                    class="size-3 shrink-0 text-gray-500 transition-transform group-data-[state=open]:rotate-180 dark:text-neutral-400"
                />
                <span class="text-xs font-medium text-gray-600 dark:text-neutral-400">
                    Object({{ Object.keys(value).length }})
                </span>
            </CollapsibleTrigger>

            <CollapsibleContent
                class="data-[state=closed]:animate-collapsible-up data-[state=open]:animate-collapsible-down overflow-hidden"
            >
                <div v-if="Object.keys(value).length > 0" class="mt-1 space-y-1 ps-4">
                    <div
                        v-for="(val, objKey) in value"
                        :key="objKey"
                        class="flex items-start gap-2 py-0.5"
                        :style="{ paddingLeft: `${(props.depth + 1) * 12}px` }"
                    >
                        <span class="min-w-25 flex-0 text-xs font-medium text-gray-600 dark:text-neutral-400">
                            {{ objKey }}:
                        </span>
                        <ObjectValue :value="val" :depth="props.depth + 1" />
                    </div>
                </div>
                <div v-else class="py-1 ps-4 text-xs text-gray-400 italic dark:text-neutral-600">
                    {{ __('global.not_found.results') }}
                </div>
            </CollapsibleContent>
        </CollapsibleRoot>
    </div>
</template>
