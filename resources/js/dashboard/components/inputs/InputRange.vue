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
    <SliderRoot class="relative flex items-center select-none touch-none w-full h-5" v-bind="forwarded">
        <SliderTrack class="bg-gray-100 dark:bg-neutral-700 relative grow rounded-full h-2">
            <SliderRange class="absolute bg-blue-700 dark:bg-blue-500 rounded-full h-full" />
        </SliderTrack>
        <SliderThumb
            class="block size-6 shadow-sm bg-white rounded-full hover:bg-stone-50 focus:outline-none"
            aria-label="Volume"
        />
    </SliderRoot>
</template>
