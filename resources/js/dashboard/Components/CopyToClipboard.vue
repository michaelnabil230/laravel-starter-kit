<script setup lang="ts">
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
import useLocalization from '@/dashboard/composables/useLocalization';
import { computed, ref } from 'vue';

const { __ } = useLocalization();

const props = withDefaults(
    defineProps<{
        text: string;
        placeHolder?: string;
        showText?: boolean;
        as?: string;
        selectAll?: boolean;
    }>(),
    {
        showText: true,
        as: 'div',
        selectAll: true,
    },
);

defineSlots<{
    default(props: { error: boolean; success: boolean }): void;
}>();

const emit = defineEmits<{
    (event: 'success'): void;
    (event: 'error'): void;
}>();

const success = ref(false);

const error = ref(false);

const copy = () => {
    navigator.clipboard
        .writeText(props.text)
        .then(() => {
            emit('success');
            success.value = true;
            setTimeout(() => (success.value = false), 3000);
        })
        .catch(() => {
            emit('error');
            error.value = true;
            setTimeout(() => (error.value = false), 3000);
        });
};

const displayText = computed<string>(() =>
    props.showText ? props.text : (props.placeHolder ?? __('global.copy_to_clipboard')),
);
</script>

<template>
    <component :is="as" class="inline-flex items-center justify-center gap-2" :class="{ 'select-all': selectAll }">
        {{ displayText }}

        <slot :error="error" :success="success" />

        <SvgIcon @click="copy" v-if="!success" name="icons/copy" class="size-4 cursor-pointer" />
        <SvgIcon v-else name="icons/check" class="size-4 text-blue-600" />
    </component>
</template>
