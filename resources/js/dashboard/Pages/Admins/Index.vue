<script setup lang="ts">
import * as Buttons from '@/dashboard/Components/Buttons/Index';
import Empty from '@/dashboard/Components/Empty.vue';
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import TextInput from '@/dashboard/Components/Inputs/TextInput.vue';
import * as Links from '@/dashboard/Components/Links/Index';
import Delete from '@/dashboard/Components/Modals/Delete.vue';
import DeleteAll from '@/dashboard/Components/Modals/DeleteAll.vue';
import Pagination from '@/dashboard/Components/Pagination.vue';
import useDeletion from '@/dashboard/composables/useDeletion';
import { Filters, useFilters } from '@/dashboard/composables/useFilter';
import useItemSelectionManager from '@/dashboard/composables/useItemSelectionManager';
import useLocalization from '@/dashboard/composables/useLocalization';
import AppLayout from '@/dashboard/Layouts/AppLayout.vue';
import { App } from '@/dashboard/types/models';
import { Link } from '@inertiajs/vue3';

const { __ } = useLocalization();

const props = defineProps<{
    filters: Filters;
    admins: App.ApiPaginate<App.Models.Admin>;
}>();

const {
    isAllSelected,
    isIndeterminate,
    selectedList,
    toggleItemSelect,
    toggleSelectAll,
    openDeleteItemsModal,
    closeDeleteItemsModal,
    confirmDeleteAll,
    resetDeletionState,
} = useItemSelectionManager('admin', props.admins.data, route('dashboard.admins.pluck-destroy'), {
    onSuccess: () => {
        dashboardApp.success(__('global.actions.deleted', { resource: __('resources.admin.singular') }));
    },
    onError: () => dashboardApp.danger(__('errors.error_happened')),
});

const { confirmDelete, openDeleteModal, closeDeleteModal } = useDeletion<App.Models.Admin>(
    'admin',
    'dashboard.admins.destroy',
    {
        onSuccess: () => {
            dashboardApp.success(__('global.actions.deleted', { resource: __('resources.admin.singular') }));
        },
        onError: () => dashboardApp.danger(__('errors.error_happened')),
        onFinish: () => resetDeletionState(),
    },
);

const { filters, updatePerPage } = useFilters(route('dashboard.admins.index'), props.filters);

const breadcrumbs: Breadcrumbs = [
    { text: __('dashboard'), href: route('dashboard.welcome') },
    { text: __('resources.admin.plural') },
];
</script>

<template>
    <AppLayout :title="__('resources.admin.plural')" :breadcrumbs="breadcrumbs">
        <div class="flex items-center justify-between gap-x-5">
            <h2 class="inline-block text-lg font-semibold text-gray-800 dark:text-neutral-200">
                {{ __('resources.admin.plural') }}
            </h2>

            <div class="flex items-center justify-end gap-x-2">
                <!-- Button -->
                <Links.Solid.Primary :href="route('dashboard.admins.create')">
                    <svg
                        class="hidden size-3 shrink-0 sm:block"
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
                            resource: __('resources.admin.singular'),
                        })
                    }}
                </Links.Solid.Primary>
                <!-- End Button -->
            </div>
        </div>

        <div
            class="flex flex-col space-y-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-800"
        >
            <div class="grid gap-y-2 md:grid-cols-2 md:gap-x-5 md:gap-y-0">
                <div>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 start-0 z-20 flex items-center ps-3.5">
                            <svg
                                class="size-4 shrink-0 text-gray-500 dark:text-neutral-400"
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
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </div>
                        <InputLabel
                            class="sr-only"
                            :value="
                                __('global.search.resource', {
                                    resource: __('resources.admin.plural'),
                                })
                            "
                            for="search_admins"
                        />
                        <TextInput
                            v-model="filters.search"
                            id="search_admins"
                            class="bg-gray-100 py-[7px] pe-8 ps-10 focus:bg-white dark:bg-neutral-700 dark:focus:bg-neutral-800"
                            :placeholder="
                                __('global.search.resource', {
                                    resource: __('resources.admin.plural'),
                                })
                            "
                        />
                    </div>
                </div>
            </div>

            <Empty
                v-if="admins.data.length === 0"
                :title="
                    __('global.not_found.resource', {
                        resource: __('resources.admin.singular'),
                    })
                "
                :description="__('global.not_found.description')"
            />

            <!-- Card -->
            <div v-else class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="inline-block min-w-full p-1.5 align-middle">
                        <div
                            class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800"
                        >
                            <!-- Header -->
                            <div
                                v-if="selectedList.length > 0"
                                class="border-b border-gray-200 px-6 py-4 dark:border-neutral-700"
                            >
                                <Buttons.White.Danger :onClick="openDeleteItemsModal">
                                    <svg
                                        class="size-4 shrink-0"
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
                                </Buttons.White.Danger>
                            </div>
                            <!-- End Header -->

                            <!-- Table -->
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th scope="col" class="py-3 ps-6 text-start">
                                            <div class="flex">
                                                <label for="all-admins" class="sr-only"> Checkbox </label>
                                                <input
                                                    id="all-admins"
                                                    type="checkbox"
                                                    :checked="isAllSelected"
                                                    :indeterminate="isIndeterminate"
                                                    @click="toggleSelectAll"
                                                    class="shrink-0 rounded border-gray-300 text-blue-700 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-600 dark:bg-neutral-800 dark:checked:border-blue-500 dark:checked:bg-blue-500 dark:focus:ring-offset-gray-800"
                                                />
                                            </div>
                                        </th>

                                        <th scope="col" class="py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                                                >
                                                    {{ __('resources.admin.attributes.name') }}
                                                </span>
                                            </div>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                                                >
                                                    {{ __('resources.admin.attributes.phone') }}
                                                </span>
                                            </div>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"
                                                >
                                                    {{ __('resources.admin.attributes.role') }}
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
                                    <tr v-for="admin in admins.data" :key="admin.id">
                                        <td
                                            class="size-px whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-neutral-500"
                                        >
                                            <div :for="'admin-' + admin.id" class="flex">
                                                <label :for="'admin-' + admin.id" class="sr-only"> Checkbox </label>
                                                <input
                                                    :id="'admin-' + admin.id"
                                                    :checked="selectedList.includes(admin)"
                                                    @click="toggleItemSelect(admin)"
                                                    type="checkbox"
                                                    class="shrink-0 rounded border-gray-300 text-blue-700 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-600 dark:bg-neutral-800 dark:checked:border-blue-500 dark:checked:bg-blue-500 dark:focus:ring-offset-gray-800"
                                                />
                                            </div>
                                        </td>
                                        <td
                                            class="size-px whitespace-nowrap py-3 text-sm text-gray-500 dark:text-neutral-500"
                                        >
                                            <div class="flex items-center gap-x-3">
                                                <img
                                                    class="inline-block size-[38px] rounded-full"
                                                    :src="admin.profile_photo_url"
                                                    alt="Avatar"
                                                />
                                                <div class="grow">
                                                    <span
                                                        class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                                                    >
                                                        {{ admin.name }}
                                                    </span>
                                                    <span class="block text-sm text-gray-500 dark:text-neutral-500">
                                                        {{ admin.email }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            class="size-px whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ admin.phone }}
                                        </td>
                                        <td
                                            class="size-px whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ __('resources.admin.enums.role.' + admin.role) }}
                                        </td>
                                        <td
                                            class="size-px whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ admin.created_at }}
                                        </td>
                                        <td class="size-px whitespace-nowrap px-6 py-3">
                                            <div class="flex items-center justify-end -space-x-px">
                                                <div
                                                    class="hs-dropdown relative inline-flex border border-stone-200 bg-white shadow-sm [--auto-close:inside] [--placement:top-right] first:rounded-s-lg last:rounded-e-lg hover:bg-stone-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-700"
                                                >
                                                    <button
                                                        :id="'menu-item-' + admin.id"
                                                        type="button"
                                                        class="inline-flex size-8 items-center justify-center gap-x-2 rounded-e-lg text-stone-800 focus:bg-stone-50 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-300 dark:focus:bg-neutral-700"
                                                        aria-haspopup="menu"
                                                        aria-expanded="false"
                                                        aria-label="Dropdown"
                                                    >
                                                        <svg
                                                            class="size-3.5 shrink-0"
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
                                                            <circle cx="12" cy="12" r="1" />
                                                            <circle cx="12" cy="5" r="1" />
                                                            <circle cx="12" cy="19" r="1" />
                                                        </svg>
                                                    </button>

                                                    <div
                                                        class="hs-dropdown-menu duration z-10 hidden w-24 rounded-xl bg-white opacity-0 shadow-[0_10px_40px_10px_rgba(0,0,0,0.08)] transition-[opacity,margin] hs-dropdown-open:opacity-100 dark:bg-neutral-900 dark:shadow-[0_10px_40px_10px_rgba(0,0,0,0.2)]"
                                                        role="menu"
                                                        aria-orientation="vertical"
                                                        :aria-labelledby="'menu-item-' + admin.id"
                                                    >
                                                        <div class="p-1">
                                                            <Link
                                                                :href="route('dashboard.admins.edit', admin.id)"
                                                                class="flex w-full items-center gap-x-3 rounded-lg px-2 py-1.5 text-[13px] text-stone-800 hover:bg-stone-100 focus:bg-stone-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                                                            >
                                                                {{ __('global.crud.edit') }}
                                                            </Link>

                                                            <div
                                                                class="my-1 border-t border-stone-200 dark:border-neutral-800"
                                                            ></div>

                                                            <button
                                                                @click="openDeleteModal(admin)"
                                                                class="flex w-full items-center gap-x-3 rounded-lg px-2 py-1.5 text-[13px] text-stone-800 hover:bg-stone-100 focus:bg-stone-100 focus:outline-none disabled:pointer-events-none disabled:opacity-50 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                                                            >
                                                                {{ __('global.crud.delete') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- End Table -->

                            <!-- Footer -->
                            <Pagination :pagination="admins" @per-page="updatePerPage" />
                            <!-- End Footer -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>

        <Delete resource="admin" @close="closeDeleteModal" @confirm="confirmDelete" />

        <DeleteAll resource="admin" @close="closeDeleteItemsModal" @confirm="confirmDeleteAll" />
    </AppLayout>
</template>
