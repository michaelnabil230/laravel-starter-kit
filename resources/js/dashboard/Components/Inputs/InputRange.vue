<script setup lang="ts">
import {
    SliderRange,
    SliderRoot,
    SliderRootEmits,
    SliderRootProps,
    SliderThumb,
    SliderTrack,
    useForwardPropsEmits,
} from 'reka-ui';

const props = defineProps<SliderRootProps>();
const emits = defineEmits<SliderRootEmits>();
const forwarded = useForwardPropsEmits(props, emits);
</script>

<template>
    <SliderRoot class="relative flex h-5 w-full touch-none items-center select-none" v-bind="forwarded">
        <SliderTrack class="relative h-2 grow rounded-full bg-gray-100 dark:bg-neutral-700">
            <SliderRange class="absolute h-full rounded-full bg-blue-700 dark:bg-blue-500" />
        </SliderTrack>
        <SliderThumb
            class="block size-6 rounded-full bg-white shadow-sm hover:bg-stone-50 focus:outline-none"
            aria-label="Volume"
        />
    </SliderRoot>
</template>
