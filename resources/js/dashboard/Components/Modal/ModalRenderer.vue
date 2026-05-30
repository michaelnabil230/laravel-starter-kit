<script setup lang="ts">
import { only } from '@/dashboard/composables/modal/helpers';
import { useModalStack } from '@/dashboard/composables/modal/modalStack';
import { modalContextKey } from '@/dashboard/composables/useModal';
import { computed, provide } from 'vue';

const props = defineProps<{ index: number }>();

const modalStack = useModalStack();

const modalContext = computed(() => {
    return modalStack.stack.value[props.index];
});

provide(modalContextKey, modalContext);
</script>

<template>
    <modalContext.component
        v-if="modalContext?.component"
        v-bind="only((modalContext as any).props ?? {}, modalContext.getComponentPropKeys(), true)"
    />
</template>
