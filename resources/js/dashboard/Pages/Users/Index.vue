<script setup lang="ts">
import BooleanBadge from '@/dashboard/Components/BooleanBadge.vue';
import ButtonLink from '@/dashboard/Components/Button/ButtonLink.vue';
import ButtonsFilters from '@/dashboard/Components/ButtonsFilters.vue';
import CopyToClipboard from '@/dashboard/Components/CopyToClipboard.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/dashboard/Components/DropdownActions';
import Empty from '@/dashboard/Components/Empty.vue';
import Pagination from '@/dashboard/Components/Pagination.vue';
import SearchTextInput from '@/dashboard/Components/SearchTextInput.vue';
import SvgIcon from '@/dashboard/Components/SvgIcon.vue';
import { Filters, useFilter } from '@/dashboard/composables/useFilter';
import useLocalization from '@/dashboard/composables/useLocalization';
import AppLayout from '@/dashboard/Layouts/AppLayout.vue';
import { App } from '@/dashboard/types/app';
import { Link } from '@inertiajs/vue3';
import FiltersModal from './Partials/Filters.vue';

const { __ } = useLocalization();

const props = defineProps<{
    filters: Filters;
    users: App.ApiPaginate<App.Models.User>;
}>();

const filter = useFilter({
    routeName: route('dashboard.users.index'),
    filters: props.filters,
    defaultFilters: {
        gender: null,
        source: null,
    },
});

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
                    <SvgIcon name="icons/plus" class="hidden sm:block" />
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

            <div v-else class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="inline-block min-w-full p-1.5 align-middle">
                        <div
                            class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
                        >
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th scope="col" class="py-3 ps-6 text-start">
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
                                                    {{ __('resources.user.attributes.birth_date') }}
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    {{ __('resources.user.table.age') }}
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    {{ __('global.gender') }}
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    {{ __('resources.user.attributes.national_id') }}
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    {{ __('resources.user.table.last_login_at') }}
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    {{ __('resources.user.attributes.is_old') }}
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span
                                                    class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-neutral-200"
                                                >
                                                    {{ __('global.source') }}
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
                                            <div class="flex flex-col gap-x-3">
                                                <Link
                                                    :href="route('dashboard.users.show', user.id)"
                                                    class="block text-sm font-semibold text-gray-800 dark:text-neutral-200"
                                                >
                                                    {{ user.name }}
                                                </Link>

                                                <a
                                                    :href="'tel:' + user.phone"
                                                    target="_blank"
                                                    class="block text-sm text-gray-500 decoration-2 hover:underline focus:underline focus:outline-hidden dark:text-neutral-500"
                                                >
                                                    {{ user.phone }}
                                                </a>
                                            </div>
                                        </td>
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ user.birth_date ?? 'N/A' }}
                                        </td>
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ user.age ?? 'N/A' }}
                                        </td>
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ __('global.enums.gender.' + user.gender) }}
                                        </td>
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            <template v-if="user.national_id">
                                                <CopyToClipboard :text="user.national_id" />
                                            </template>
                                            <template v-else>N/A</template>
                                        </td>
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ user.last_login_at ?? 'N/A' }}
                                        </td>
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            <BooleanBadge :boolean="user.is_old" />
                                        </td>
                                        <td
                                            class="size-px px-6 py-3 text-sm whitespace-nowrap text-gray-500 dark:text-neutral-500"
                                        >
                                            {{ __('global.enums.source.' + user.source) }}
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
                                                    <DropdownMenuItem
                                                        :as="Link"
                                                        :href="route('dashboard.users.family-members.index', user.id)"
                                                    >
                                                        {{ __('resources.family_member.plural') }}
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem
                                                        :as="Link"
                                                        :href="route('dashboard.invoices.index', { user_id: user.id })"
                                                    >
                                                        {{ __('resources.invoice.plural') }}
                                                    </DropdownMenuItem>
                                                </DropdownMenuContent>
                                            </DropdownMenu>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <Pagination :pagination="users" :filter="filter" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <FiltersModal :filter="filter" />
    </AppLayout>
</template>
