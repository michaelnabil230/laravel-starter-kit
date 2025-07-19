<script setup lang="ts">
import { SwitchRoot, SwitchThumb } from 'reka-ui';
import { type HTMLAttributes } from 'vue';

withDefaults(
    defineProps<{
        id: string;
        class?: HTMLAttributes['class'];
        disabled?: boolean;
    }>(),
    {
        disabled: false,
    },
);

const model = defineModel<boolean>();
</script>

<template>
    <SwitchRoot
        :id="id"
        :disabled="disabled"
        v-model="model"
        class="relative h-6 w-11 cursor-pointer rounded-full transition-colors ease-in-out data-[disabled]:pointer-events-none data-[disabled]:opacity-50 data-[state=checked]:bg-blue-600 data-[state=unchecked]:bg-gray-200 dark:data-[state=checked]:bg-blue-500 dark:data-[state=unchecked]:bg-neutral-700"
    >
        <SwitchThumb
            class="absolute start-0.5 top-1/2 size-5 -translate-y-1/2 rounded-full bg-white shadow-xs transition-transform duration-200 ease-in-out will-change-transform data-[state=checked]:translate-x-full dark:bg-neutral-400 dark:data-[state=checked]:bg-white"
        />
    </SwitchRoot>
</template>
