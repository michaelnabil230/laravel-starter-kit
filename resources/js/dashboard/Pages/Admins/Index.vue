<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import ButtonLink from '@/dashboard/Components/Button/ButtonLink.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/dashboard/Components/DropdownActions';
import Empty from '@/dashboard/Components/Empty.vue';
import Checkbox from '@/dashboard/Components/Inputs/Checkbox.vue';
import Delete from '@/dashboard/Components/Modals/Delete.vue';
import DeleteAll from '@/dashboard/Components/Modals/DeleteAll.vue';
import NameCircle from '@/dashboard/Components/NameCircle/NameCircle.vue';
import Pagination from '@/dashboard/Components/Pagination.vue';
import SearchTextInput from '@/dashboard/Components/SearchTextInput.vue';
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
import { useDeletion } from '@/dashboard/composables/useDeletion';
import { Filters, useFilter } from '@/dashboard/composables/useFilter';
import useItemSelectionManager from '@/dashboard/composables/useItemSelectionManager';
import useLocalization from '@/dashboard/composables/useLocalization';
import useToasts from '@/dashboard/composables/useToasts';
import AppLayout from '@/dashboard/Layouts/AppLayout.vue';
import { App } from '@/dashboard/types/app';
import { Link } from '@inertiajs/vue3';

const { __ } = useLocalization();
const toasts = useToasts();

const props = defineProps<{
    filters: Filters;
    admins: App.ApiPaginate<App.Models.Admin>;
}>();

const { isAllSelected, isIndeterminate, selectedList, toggleItemSelect, toggleSelectAll, resetItemSelection } =
    useItemSelectionManager(props.admins.data);

const { confirmDelete, confirmDeleteAll, openDeleteItemsModal, openDeleteModal } = useDeletion<App.Models.Admin>({
    resource: 'admin',
    routeName: (id) => route('dashboard.admins.destroy', id),
    routeDeleteAll: () => route('dashboard.admins.pluck-destroy'),
    options: {
        onSuccess: () => toasts.success(__('global.actions.deleted', { resource: __('resources.admin.singular') })),
        onError: () => toasts.danger(__('errors.error_happened')),
        onFinish: () => resetItemSelection(),
    },
});

const filter = useFilter({ routeName: route('dashboard.admins.index'), filters: props.filters });

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
                <ButtonLink :href="route('dashboard.admins.create')">
                    <SvgIcon name="icons/plus" class="size-4 shrink-0" />
                    {{
                        __('global.resource.create', {
                            resource: __('resources.admin.singular'),
                        })
                    }}
                </ButtonLink>
            </div>
        </div>

        <div
            class="flex flex-col space-y-4 rounded-xl border border-gray-200 bg-white p-5 shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
        >
            <div class="grid gap-y-2 md:grid-cols-2 md:gap-x-5 md:gap-y-0">
                <SearchTextInput resource="admin" v-model="filter.queryBuilderData.value.search" />
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

            <div v-else class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="inline-block min-w-full p-1.5 align-middle">
                        <div
                            class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
                        >
                            <div
                                v-if="selectedList.length > 0"
                                class="border-b border-gray-200 px-6 py-4 dark:border-neutral-700"
                            >
                                <Button variant="text" color="danger" :onClick="openDeleteItemsModal">
                                    <SvgIcon name="icons/trash" />
                                    {{ __('global.crud.delete') }} ({{ selectedList.length }})
                                </Button>
                            </div>

                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th scope="col" class="py-3 ps-6 text-start">
                                            <div class="flex">
                                                <label for="all-admins" class="sr-only"> Checkbox </label>
                                                <Checkbox
                                                    id="all-admins"
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
                                                    {{ __('resources.admin.attributes.name') }}
                                                </span>
                                            </div>
                                        </th>

                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    {{ __('resources.admin.attributes.role') }}
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
                                    <tr v-for="admin in admins.data" :key="admin.id">
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            <div :for="'admin-' + admin.id" class="flex">
                                                <label :for="'admin-' + admin.id" class="sr-only"> Checkbox </label>
                                                <Checkbox
                                                    :id="'admin-' + admin.id"
                                                    :checked="selectedList.includes(admin.id)"
                                                    @click="toggleItemSelect(admin.id)"
                                                />
                                            </div>
                                        </td>
                                        <td
                                            class="size-px py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            <div class="flex items-center gap-x-3">
                                                <NameCircle :name="admin.name" size="medium" />
                                                <div class="grow">
                                                    <span
                                                        class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                                                    >
                                                        {{ admin.name }}
                                                    </span>
                                                    <a
                                                        :href="'mailto:' + admin.email"
                                                        target="_blank"
                                                        class="block text-sm text-gray-500 decoration-2 hover:underline focus:underline focus:outline-hidden dark:text-neutral-500"
                                                    >
                                                        {{ admin.email }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ __('resources.admin.enums.role.' + admin.role) }}
                                        </td>
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ admin.created_at }}
                                        </td>
                                        <td class="size-px px-6 py-3 whitespace-nowrap">
                                            <DropdownMenu>
                                                <DropdownMenuTrigger />

                                                <DropdownMenuContent>
                                                    <DropdownMenuItem
                                                        :as="Link"
                                                        :href="route('dashboard.admins.edit', admin.id)"
                                                    >
                                                        {{ __('global.crud.edit') }}
                                                    </DropdownMenuItem>

                                                    <DropdownMenuSeparator />

                                                    <DropdownMenuItem as="button" @click="openDeleteModal(admin)">
                                                        {{ __('global.crud.delete') }}
                                                    </DropdownMenuItem>
                                                </DropdownMenuContent>
                                            </DropdownMenu>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <Pagination :pagination="admins" :filter="filter" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Delete resource="admin" @confirm="confirmDelete" />

        <DeleteAll resource="admin" @confirm="() => confirmDeleteAll(selectedList)" />
    </AppLayout>
</template>
