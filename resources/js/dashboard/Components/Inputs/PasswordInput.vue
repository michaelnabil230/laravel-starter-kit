<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
import { useForwardProps } from 'reka-ui';
import { ref } from 'vue';
import TextInput from './TextInput.vue';
import { type InputWithDefaultValueProps } from './types';

const props = withDefaults(defineProps<InputWithDefaultValueProps>(), {
    hasError: false,
});

const forwardedProps = useForwardProps(props);

const model = defineModel<string | null>();

if (model.value === undefined) {
    model.value = props.defaultValue ?? null;
}

const toggle = ref(false);
</script>

<template>
    <TextInput v-model="model" :type="toggle ? 'text' : 'password'" v-bind="forwardedProps" placeholder="********">
        <template #icon>
            <button
                type="button"
                @click="toggle = !toggle"
                class="cursor-pointer text-gray-400 focus:text-blue-700 focus:outline-hidden dark:text-neutral-600 dark:focus:text-blue-500"
            >
                <SvgIcon v-if="toggle" name="icons/eye-open" class="size-4 shrink-0" />
                <SvgIcon v-if="!toggle" name="icons/eye-closed" class="size-4 shrink-0" />
            </button>
        </template>
    </TextInput>
</template>
