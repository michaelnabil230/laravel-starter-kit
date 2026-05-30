<script setup lang="ts">
import ButtonLink from '@/dashboard/Components/Button/ButtonLink.vue';
import Empty from '@/dashboard/Components/Empty.vue';
import Pagination from '@/dashboard/Components/Pagination.vue';
import SearchTextInput from '@/dashboard/Components/SearchTextInput.vue';
import { Filters, useFilter } from '@/dashboard/composables/useFilter';
import useLocalization from '@/dashboard/composables/useLocalization';
import AppLayout from '@/dashboard/Layouts/AppLayout.vue';
import { App } from '@/dashboard/types/app';

const { __ } = useLocalization();

const props = defineProps<{
    filters: Filters;
    activityLogs: App.ApiPaginate<App.Models.ActivityLog>;
}>();

const filter = useFilter({ routeName: route('dashboard.activity-logs.index'), filters: props.filters });

const breadcrumbs: Breadcrumbs = [
    { text: __('dashboard'), href: route('dashboard.welcome') },
    { text: __('resources.activity_log.plural') },
];
</script>

<template>
    <AppLayout :title="__('resources.activity_log.plural')" :breadcrumbs="breadcrumbs">
        <div class="flex items-center justify-between gap-x-5">
            <h2 class="inline-block text-lg font-semibold text-gray-800 dark:text-neutral-200">
                {{ __('resources.activity_log.plural') }}
            </h2>
        </div>

        <div
            class="flex flex-col space-y-4 rounded-xl border border-gray-200 bg-white p-5 shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
        >
            <div class="grid gap-y-2 md:grid-cols-2 md:gap-x-5 md:gap-y-0">
                <SearchTextInput resource="activity_log" v-model="filter.queryBuilderData.value.search" />
            </div>

            <Empty
                v-if="activityLogs.data.length === 0"
                :title="
                    __('global.not_found.resource', {
                        resource: __('resources.activity_log.singular'),
                    })
                "
                :description="__('global.not_found.description')"
            />

            <div v-else class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="inline-block min-w-full p-1.5 align-middle">
                        <div
                            class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
                        >
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    #
                                                </span>
                                            </div>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    {{ __('resources.activity_log.attributes.log_name') }}
                                                </span>
                                            </div>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    {{ __('resources.activity_log.attributes.description') }}
                                                </span>
                                            </div>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    {{ __('resources.activity_log.attributes.subject_type') }}
                                                </span>
                                            </div>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    {{ __('resources.activity_log.attributes.event') }}
                                                </span>
                                            </div>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    {{ __('resources.activity_log.attributes.causer') }}
                                                </span>
                                            </div>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    {{ __('global.created_at') }}
                                                </span>
                                            </div>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-end"></th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr v-for="activityLog in activityLogs.data" :key="activityLog.id">
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ activityLog.id }}
                                        </td>
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ activityLog.log_name }}
                                        </td>

                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ activityLog.description }}
                                        </td>
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ activityLog.subject_type }}
                                        </td>
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ activityLog.event }}
                                        </td>
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ activityLog.causer?.name ?? 'N/A' }}
                                        </td>
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ activityLog.created_at }}
                                        </td>
                                        <td class="size-px px-6 py-3 whitespace-nowrap">
                                            <ButtonLink
                                                :href="route('dashboard.activity-logs.show', activityLog.id)"
                                                variant="outlined"
                                                color="secondary"
                                                :value="__('global.crud.show')"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <Pagination :pagination="activityLogs" :filter="filter" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
