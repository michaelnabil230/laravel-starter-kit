<script setup lang="ts">
import Button from '@/dashboard/components/button/Button.vue';
import ButtonLink from '@/dashboard/components/button/ButtonLink.vue';
import ButtonsFilters from '@/dashboard/components/ButtonsFilters.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/dashboard/components/dropdownActions';
import Empty from '@/dashboard/components/Empty.vue';
import ExportButton from '@/dashboard/components/ExportButton.vue';
import Checkbox from '@/dashboard/components/inputs/Checkbox.vue';
import Delete from '@/dashboard/components/modals/Delete.vue';
import DeleteAll from '@/dashboard/components/modals/DeleteAll.vue';
import Export from '@/dashboard/components/modals/Export.vue';
import Pagination from '@/dashboard/components/Pagination.vue';
import SearchTextInput from '@/dashboard/components/SearchTextInput.vue';
import useDeletion from '@/dashboard/composables/useDeletion';
import useExport from '@/dashboard/composables/useExport';
import { Filters, useFilter } from '@/dashboard/composables/useFilter';
import useItemSelectionManager from '@/dashboard/composables/useItemSelectionManager';
import useLocalization from '@/dashboard/composables/useLocalization';
import AppLayout from '@/dashboard/layouts/AppLayout.vue';
import { App } from '@/dashboard/types/models';
import { Link } from '@inertiajs/vue3';
import FiltersModal from './partials/Filters.vue';

const { __ } = useLocalization();

const props = defineProps<{
    filters: Filters;
    users: App.ApiPaginate<App.Models.User>;
}>();

const {
    isAllSelected,
    isIndeterminate,
    selectedList,
    openDeleteItems,
    toggleItemSelect,
    toggleSelectAll,
    openDeleteItemsModal,
    closeDeleteItemsModal,
    confirmDeleteAll,
    resetItemSelection,
} = useItemSelectionManager(props.users.data, route('dashboard.users.pluck-destroy'), {
    onSuccess: () => {
        dashboardApp.success(__('global.actions.deleted', { resource: __('resources.user.singular') }));
    },
    onError: () => dashboardApp.danger(__('errors.error_happened')),
});

const { openDelete, confirmDelete, openDeleteModal, closeDeleteModal } = useDeletion<App.Models.User>(
    'dashboard.users.destroy',
    {
        onSuccess: () => {
            dashboardApp.success(__('global.actions.deleted', { resource: __('resources.user.singular') }));
        },
        onError: () => dashboardApp.danger(__('errors.error_happened')),
        onFinish: () => resetItemSelection(),
    },
);

const filter = useFilter(route('dashboard.users.index'), props.filters);

const { openExport, openExportModal, closeExportModal, confirmExport } = useExport(
    route('dashboard.users.export'),
    filter.queryBuilderData,
);

const breadcrumbs: Breadcrumbs = [
    { text: __('dashboard'), href: route('dashboard.welcome') },
    { text: __('resources.user.plural') },
];
</script>

<template>
    <AppLayout :title="__('resources.user.plural')" :breadcrumbs="breadcrumbs">
        <div class="flex items-center justify-between gap-x-5">
            <h2 class="inline-block text-lg font-semibold text-gray-800 dark:text-neutral-200">
                {{ __('resources.user.plural') }}
            </h2>

            <div class="flex items-center justify-end gap-x-2">
                <ButtonLink :href="route('dashboard.users.create')">
                    <svg
                        class="hidden sm:block"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        viewBox="0 0 16 16"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M8 1C8.55228 1 9 1.44772 9 2V7L14 7C14.5523 7 15 7.44771 15 8C15 8.55228 14.5523 9 14 9L9 9V14C9 14.5523 8.55228 15 8 15C7.44772 15 7 14.5523 7 14V9.00001L2 9.00001C1.44772 9.00001 1 8.5523 1 8.00001C0.999999 7.44773 1.44771 7.00001 2 7.00001L7 7.00001V2C7 1.44772 7.44772 1 8 1Z"
                        />
                    </svg>
                    {{
                        __('global.resource.create', {
                            resource: __('resources.user.singular'),
                        })
                    }}
                </ButtonLink>
            </div>
        </div>

        <div
            class="flex flex-col space-y-4 rounded-xl border border-gray-200 bg-white p-5 shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
        >
            <div class="grid gap-y-2 md:grid-cols-2 md:gap-x-5 md:gap-y-0">
                <SearchTextInput resource="user" v-model="filter.queryBuilderData.value.search" />

                <div class="flex items-center justify-end gap-x-2">
                    <!-- Export Button -->
                    <ExportButton :click="openExportModal" :resource="__('resources.user.plural')" />
                    <!-- End Export Button -->

                    <ButtonsFilters :filter="filter" />
                </div>
            </div>

            <Empty
                v-if="users.data.length === 0"
                :title="
                    __('global.not_found.resource', {
                        resource: __('resources.user.singular'),
                    })
                "
                :description="__('global.not_found.description')"
            />

            <!-- Card -->
            <div v-else class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="inline-block min-w-full p-1.5 align-middle">
                        <div
                            class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
                        >
                            <!-- Header -->
                            <div
                                v-if="selectedList.length > 0"
                                class="border-b border-gray-200 px-6 py-4 dark:border-neutral-700"
                            >
                                <Button variant="text" color="danger" :onClick="openDeleteItemsModal">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path d="M3 6h18" />
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                        <line x1="10" x2="10" y1="11" y2="17" />
                                        <line x1="14" x2="14" y1="11" y2="17" />
                                    </svg>
                                    {{ __('global.crud.delete') }} ({{ selectedList.length }})
                                </Button>
                            </div>
                            <!-- End Header -->

                            <!-- Table -->
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th scope="col" class="py-3 ps-6 text-start">
                                            <div class="flex">
                                                <label for="all-users" class="sr-only"> Checkbox </label>
                                                <Checkbox
                                                    id="all-users"
                                                    :checked="isAllSelected"
                                                    :indeterminate="isIndeterminate"
                                                    @click="toggleSelectAll"
                                                />
                                            </div>
                                        </th>

                                        <th scope="col" class="py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                                                >
                                                    {{ __('resources.user.attributes.name') }}
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                                                >
                                                    {{ __('resources.user.attributes.phone') }}
                                                </span>
                                            </div>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                                                >
                                                    {{ __('global.created_at') }}
                                                </span>
                                            </div>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-end"></th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                    <tr v-for="user in users.data" :key="user.id">
                                        <td
                                            class="size-px whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-neutral-500"
                                        >
                                            <div :for="'user-' + user.id" class="flex">
                                                <label :for="'user-' + user.id" class="sr-only"> Checkbox </label>
                                                <Checkbox
                                                    :id="'user-' + user.id"
                                                    :checked="selectedList.includes(user)"
                                                    @click="toggleItemSelect(user)"
                                                />
                                            </div>
                                        </td>
                                        <td
                                            class="size-px whitespace-nowrap py-3 text-sm text-gray-500 dark:text-neutral-500"
                                        >
                                            <div class="flex items-center gap-x-3">
                                                <img
                                                    class="inline-block size-[38px] rounded-full object-cover"
                                                    :src="user.profile_photo_url"
                                                    alt="Avatar"
                                                />
                                                <div class="grow">
                                                    <span
                                                        class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                                                    >
                                                        {{ user.name }}
                                                    </span>
                                                    <a
                                                        :href="'mailto:' + user.email"
                                                        target="_blank"
                                                        class="decoration-2 hover:underline focus:underline focus:outline-hidden block text-sm text-gray-500 dark:text-neutral-500"
                                                    >
                                                        {{ user.email }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            dir="ltr"
                                            class="size-px rtl:text-end text-start whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-neutral-500"
                                        >
                                            <a
                                                :href="'tel:' + user.phone"
                                                target="_blank"
                                                class="decoration-2 hover:underline focus:underline focus:outline-hidden"
                                            >
                                                {{ user.phone }}
                                            </a>
                                        </td>
                                        <td
                                            class="size-px whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ user.created_at }}
                                        </td>
                                        <td class="size-px whitespace-nowrap px-6 py-3">
                                            <DropdownMenu>
                                                <DropdownMenuTrigger />

                                                <DropdownMenuContent>
                                                    <DropdownMenuItem
                                                        :as="Link"
                                                        :href="route('dashboard.users.show', user.id)"
                                                        preserve-scroll
                                                        preserve-state
                                                        :data-disabled="$loading.is"
                                                    >
                                                        {{ __('global.crud.show') }}
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem
                                                        :as="Link"
                                                        :href="route('dashboard.users.edit', user.id)"
                                                        preserve-scroll
                                                        preserve-state
                                                        :data-disabled="$loading.is"
                                                    >
                                                        {{ __('global.crud.edit') }}
                                                    </DropdownMenuItem>

                                                    <DropdownMenuSeparator />

                                                    <DropdownMenuItem as="button" @click="openDeleteModal(user)">
                                                        {{ __('global.crud.delete') }}
                                                    </DropdownMenuItem>
                                                </DropdownMenuContent>
                                            </DropdownMenu>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- End Table -->

                            <!-- Footer -->
                            <Pagination :pagination="users" :filter="filter" />
                            <!-- End Footer -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>

        <Delete v-model="openDelete" resource="user" @close="closeDeleteModal" @confirm="confirmDelete" />

        <DeleteAll
            v-model="openDeleteItems"
            resource="user"
            @close="closeDeleteItemsModal"
            @confirm="confirmDeleteAll"
        />

        <Export v-model="openExport" resource="user" @close="closeExportModal" @confirm="confirmExport" />

        <FiltersModal :filter="filter" />
    </AppLayout>
</template>
