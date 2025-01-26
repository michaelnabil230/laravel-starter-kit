<script setup lang="ts">
import HSRangeSlider from '@preline/range-slider';
import { computed, nextTick, onMounted, ref } from 'vue';

const model = defineModel<number>({ required: true });

const input = ref<HTMLInputElement | null>(null);

const props = defineProps<{
    start: number;
    range: {
        min: number;
        max: number;
    };
}>();

onMounted(() => {
    nextTick(() => {
        const { element } = HSRangeSlider.getInstance(input.value as HTMLElement, true) as any;

        element.el.noUiSlider.on('update', (values: any) => {
            model.value = parseFloat(element.formattedValue);
        });
    });
});

const hsRangeOptions = computed(() => {
    return {
        start: props.start,
        range: props.range,
        connect: 'lower',
        tooltips: true,
        formatter: 'integer',
        cssClasses: {
            target: 'relative h-2 rounded-full bg-gray-100 dark:bg-neutral-700',
            base: 'w-full h-full relative z-1',
            origin: 'absolute top-0 end-0 w-full h-full origin-[0_0] rounded-full',
            handle: 'absolute top-1/2 end-0 w-[1.125rem] h-[1.125rem] bg-white border-4 border-blue-700 rounded-full cursor-pointer translate-x-2/4 -translate-y-2/4 dark:border-blue-500',
            connects: 'relative z-0 w-full h-full rounded-full overflow-hidden',
            connect: 'absolute top-0 end-0 z-1 w-full h-full bg-blue-700 origin-[0_0] dark:bg-blue-500',
            touchArea: 'absolute -top-1 -bottom-1 -start-1 -end-1',
            tooltip:
                'bg-white border border-gray-200 text-sm text-gray-800 py-1 px-2 rounded-lg mb-3 absolute bottom-full start-2/4 -translate-x-2/4 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white',
        },
    };
});
</script>

<template>
    <div>
        <label class="sr-only">Range</label>
        <div ref="input" id="range-slider" :data-hs-range-slider="JSON.stringify(hsRangeOptions)"></div>
    </div>
</template>
