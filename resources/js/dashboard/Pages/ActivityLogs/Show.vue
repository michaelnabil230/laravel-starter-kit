<script setup lang="ts">
import ButtonLink from '@/dashboard/Components/Button/ButtonLink.vue';
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
import useLocalization from '@/dashboard/composables/useLocalization';
import AppLayout from '@/dashboard/Layouts/AppLayout.vue';
import { App } from '@/dashboard/types/app';
import ValuesTable from './Components/ValuesTable.vue';

const { __ } = useLocalization();

const breadcrumbs: Breadcrumbs = [
    { text: __('dashboard'), href: route('dashboard.welcome') },
    { text: __('resources.activity_log.plural'), href: route('dashboard.activity-logs.index') },
    { text: __('global.crud.show') },
];

defineProps<{
    activityLog: App.Models.ActivityLog;
}>();
</script>

<template>
    <AppLayout :title="__('resources.activity_log.singular')" :breadcrumbs="breadcrumbs">
        <div class="space-y-5">
            <div class="flex items-center justify-between gap-x-5">
                <h2 class="inline-block text-lg font-semibold text-gray-800 dark:text-neutral-200">
                    {{ __('resources.activity_log.singular') }}
                </h2>

                <ButtonLink
                    v-if="['updated', 'deleted'].includes(activityLog.event)"
                    :href="route('dashboard.activity-logs.restore', activityLog.id)"
                    method="post"
                    color="danger"
                >
                    <SvgIcon name="icons/danger" class="size-4 shrink-0" />
                    {{ __('global.options.restore.restore') }}
                </ButtonLink>
            </div>

            <div
                class="rounded-xl border border-stone-200 bg-white px-5 py-3 shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
            >
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div class="flex flex-col gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase dark:text-neutral-500">
                            {{ __('resources.activity_log.attributes.log_name') }}
                        </span>
                        <p class="text-sm text-gray-800 dark:text-neutral-200">
                            {{ activityLog.log_name }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase dark:text-neutral-500">
                            {{ __('resources.activity_log.attributes.description') }}
                        </span>
                        <p class="text-sm text-gray-800 dark:text-neutral-200">
                            {{ activityLog.description }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase dark:text-neutral-500">
                            {{ __('resources.activity_log.attributes.event') }}
                        </span>
                        <p class="text-sm text-gray-800 dark:text-neutral-200">
                            {{ activityLog.event }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase dark:text-neutral-500">
                            {{ __('resources.activity_log.attributes.subject_type') }}
                        </span>
                        <p class="text-sm text-gray-800 dark:text-neutral-200">
                            {{ activityLog.subject_type }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase dark:text-neutral-500">
                            {{ __('resources.activity_log.attributes.causer') }}
                        </span>
                        <p class="text-sm text-gray-800 dark:text-neutral-200">
                            {{ activityLog.causer?.name ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase dark:text-neutral-500">
                            {{ __('global.created_at') }}
                        </span>
                        <p class="text-sm text-gray-800 dark:text-neutral-200">
                            {{ activityLog.created_at }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                <div>
                    <ValuesTable
                        :title="__('resources.activity_log.attributes.old_values')"
                        :data="(activityLog.attribute_changes.old as Record<string, any>) || {}"
                    />
                </div>

                <div>
                    <ValuesTable
                        :title="__('resources.activity_log.attributes.new_values')"
                        :data="(activityLog.attribute_changes.attributes as Record<string, any>) || {}"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
