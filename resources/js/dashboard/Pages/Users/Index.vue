<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import ButtonLink from '@/dashboard/Components/Button/ButtonLink.vue';
import ButtonsFilters from '@/dashboard/Components/ButtonsFilters.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/dashboard/Components/DropdownActions';
import Empty from '@/dashboard/Components/Empty.vue';
import ImportExportButton from '@/dashboard/Components/ImportExportButton.vue';
import Checkbox from '@/dashboard/Components/Inputs/Checkbox.vue';
import Delete from '@/dashboard/Components/Modals/Delete.vue';
import DeleteAll from '@/dashboard/Components/Modals/DeleteAll.vue';
import Export from '@/dashboard/Components/Modals/Export.vue';
import Import from '@/dashboard/Components/Modals/Import.vue';
import Pagination from '@/dashboard/Components/Pagination.vue';
import SearchTextInput from '@/dashboard/Components/SearchTextInput.vue';
import useDeletion from '@/dashboard/composables/useDeletion';
import useExport from '@/dashboard/composables/useExport';
import { Filters, useFilter } from '@/dashboard/composables/useFilter';
import useImport from '@/dashboard/composables/useImport';
import useItemSelectionManager from '@/dashboard/composables/useItemSelectionManager';
import useLocalization from '@/dashboard/composables/useLocalization';
import useToasts from '@/dashboard/composables/useToasts';
import AppLayout from '@/dashboard/Layouts/AppLayout.vue';
import { App } from '@/dashboard/types/app';
import { Link } from '@inertiajs/vue3';
import FiltersModal from './Partials/Filters.vue';

const { __ } = useLocalization();
const toasts = useToasts();

const props = defineProps<{
    filters: Filters;
    users: App.ApiPaginate<App.Models.User>;
}>();

const { isAllSelected, isIndeterminate, selectedList, toggleItemSelect, toggleSelectAll, resetItemSelection } =
    useItemSelectionManager(props.users.data);

const {
    openDelete,
    openDeleteItems,
    confirmDelete,
    confirmDeleteAll,
    openDeleteItemsModal,
    closeDeleteItemsModal,
    openDeleteModal,
    closeDeleteModal,
} = useDeletion<App.Models.User>({
    routeName: 'dashboard.users.destroy',
    routeDeleteAll: 'dashboard.users.pluck-destroy',
    options: {
        onSuccess: () => toasts.success(__('global.actions.deleted', { resource: __('resources.user.singular') })),
        onError: () => toasts.danger(__('errors.error_happened')),
        onFinish: () => resetItemSelection(),
    },
});

const filter = useFilter(route('dashboard.users.index'), props.filters, {
    gender: '',
});

const { openExport, openExportModal, closeExportModal, confirmExport } = useExport(
    route('dashboard.users.export'),
    filter.queryBuilderData,
);

const { openImport, openImportModal, closeImportModal, confirmImport } = useImport(route('dashboard.users.import'));

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
                    <!-- Import & Export Button -->
                    <ImportExportButton
                        :resource="__('resources.user.plural')"
                        :onExport="openExportModal"
                        :onImport="openImportModal"
                    />
                    <!-- End Import & Export Button -->

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
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    {{ __('resources.user.attributes.name') }}
                                                </span>
                                            </div>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    {{ __('resources.user.attributes.phone') }}
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
                                    <tr v-for="user in users.data" :key="user.id">
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            <div :for="'user-' + user.id" class="flex">
                                                <label :for="'user-' + user.id" class="sr-only"> Checkbox </label>
                                                <Checkbox
                                                    :id="'user-' + user.id"
                                                    :checked="selectedList.includes(user.id)"
                                                    @click="toggleItemSelect(user.id)"
                                                />
                                            </div>
                                        </td>
                                        <td
                                            class="size-px py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
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
                                                        class="block text-sm text-gray-500 decoration-2 hover:underline focus:underline focus:outline-hidden dark:text-neutral-500"
                                                    >
                                                        {{ user.email }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            dir="ltr"
                                            class="size-px px-6 py-3 text-start text-sm whitespace-nowrap text-gray-500 rtl:text-end dark:text-neutral-500"
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
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ user.created_at }}
                                        </td>
                                        <td class="size-px px-6 py-3 whitespace-nowrap">
                                            <DropdownMenu>
                                                <DropdownMenuTrigger />

                                                <DropdownMenuContent>
                                                    <DropdownMenuItem
                                                        :as="Link"
                                                        :href="route('dashboard.users.show', user.id)"
                                                    >
                                                        {{ __('global.crud.show') }}
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem
                                                        :as="Link"
                                                        :href="route('dashboard.users.edit', user.id)"
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
            @confirm="() => confirmDeleteAll(selectedList)"
        />

        <Import v-model="openImport" resource="user" @close="closeImportModal" @confirm="confirmImport" />

        <Export v-model="openExport" resource="user" @close="closeExportModal" @confirm="confirmExport" />

        <FiltersModal :filter="filter" />
    </AppLayout>
</template>
