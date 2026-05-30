<script setup lang="ts">
import { useForwardProps } from 'reka-ui';
import { onMounted, watch } from 'vue';
import TextInput from './TextInput.vue';
import { type InputWithDefaultValueProps } from './types';

defineOptions({ inheritAttrs: false });

const props = withDefaults(defineProps<InputWithDefaultValueProps>(), {
    hasError: false,
});

const forwardedProps = useForwardProps(props);

const model = defineModel<string | number | null>();

if (model.value === undefined) {
    model.value = props.defaultValue ?? null;
}

onMounted(() => {
    if (model.value) {
        formatPhoneNumber(String(model.value));
    }
});

watch(model, (newValue) => {
    if (newValue) {
        formatPhoneNumber(String(newValue));
    }
});

const formatPhoneNumber = (value: string) => {
    const normalized = normalizeSaudiPhone(value);

    if (normalized) {
        model.value = `+966${normalized}`;
    } else {
        model.value = '';
    }
};

const normalizeSaudiPhone = (value: string) => {
    let text = value.replace(/\D/g, '');

    if (text.startsWith('966')) {
        text = text.substring(3);
    }

    if (text.startsWith('0')) {
        text = text.substring(1);
    }

    if (text.length > 9) {
        text = text.substring(0, 9);
    }

    return text;
};
</script>

<template>
    <div dir="ltr">
        <TextInput v-model="model" v-bind="forwardedProps" type="tel" />
    </div>
</template>
