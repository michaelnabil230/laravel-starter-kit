<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
import { cn } from '@/dashboard/lib/utils';
import { useForwardProps } from 'reka-ui';
import { computed, useAttrs } from 'vue';
import { type InputWithDefaultValueProps } from './types';

defineOptions({ inheritAttrs: false });

const attrs = useAttrs();

const props = withDefaults(
    defineProps<
        InputWithDefaultValueProps & {
            type: string;
        }
    >(),
    {
        hasError: false,
    },
);

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, hasError, defaultValue, ...delegated } = { ...attrs, ...props };

    return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);

const model = defineModel<string | number | null>();

if (model.value === undefined) {
    model.value = props.defaultValue ?? null;
}
</script>

<template>
    <div class="relative">
        <input
            v-model="model"
            v-bind="forwardedProps"
            autocomplete="off"
            :class="
                cn(
                    'block w-full rounded-lg border-gray-200 px-3 py-2 text-sm placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-transparent dark:text-neutral-300 dark:placeholder:text-white/60 dark:focus:ring-neutral-600',
                    props.class,
                    {
                        'border-red-500 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:focus:border-red-500 dark:focus:ring-red-500':
                            hasError,
                    },
                )
            "
        />

        <div class="absolute inset-y-0 inset-e-0 z-20 flex cursor-pointer items-center gap-2 pe-4">
            <slot name="icon" />

            <SvgIcon v-if="hasError" name="icons/warning" class="size-4 shrink-0 text-red-500" />
        </div>
    </div>
</template>
