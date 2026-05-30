<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import ButtonLink from '@/dashboard/Components/Button/ButtonLink.vue';
import useLocalization from '@/dashboard/composables/useLocalization';
import AppLayout from '@/dashboard/Layouts/AppLayout.vue';
import { ref } from 'vue';
import BaseCreate from './Partials/BaseCreate.vue';

const { __ } = useLocalization();

const breadcrumbs: Breadcrumbs = [
    { text: __('dashboard'), href: route('dashboard.welcome') },
    { text: __('resources.user.plural'), href: route('dashboard.users.index') },
    { text: __('global.crud.create') },
];

const baseCreateRef = ref<InstanceType<typeof BaseCreate> | null>(null);

const handleSubmit = () => {
    baseCreateRef.value?.submit();
};
</script>

<template>
    <AppLayout
        :title="
            __('global.resource.create', {
                resource: __('resources.user.singular'),
            })
        "
        :breadcrumbs="breadcrumbs"
    >
        <form
            @submit.prevent="handleSubmit"
            class="flex flex-col rounded-xl border border-stone-200 bg-white shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
        >
            <div
                class="flex items-center justify-between gap-x-5 border-b border-stone-200 px-5 py-3 dark:border-neutral-700"
            >
                <h2 class="inline-block font-semibold text-stone-800 dark:text-neutral-200">
                    {{
                        __('global.resource.create', {
                            resource: __('resources.user.singular'),
                        })
                    }}
                </h2>
            </div>

            <BaseCreate :asModal="false" ref="baseCreateRef" />

            <div
                class="flex items-center justify-end gap-x-2.5 border-t border-stone-200 px-5 py-3 dark:border-neutral-700"
            >
                <ButtonLink
                    color="secondary"
                    variant="outlined"
                    :href="route('dashboard.users.index')"
                    :value="__('global.closure.cancel')"
                />

                <Button type="submit" :value="__('global.crud.create')" :disabled="baseCreateRef?.form?.processing" />
            </div>
        </form>
    </AppLayout>
</template>
