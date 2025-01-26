<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const modal = ref(false);

const open = () => (modal.value = true);

const close = () => (modal.value = false);

window.matchMedia('(max-width: 768px)').addEventListener('change', close);
</script>

<template>
    <Teleport defer to="#button-sidebar">
        <button
            @click="open"
            type="button"
            class="inline-flex h-[38px] w-7 cursor-pointer items-center justify-center gap-x-2 rounded-lg border border-gray-200 bg-white text-sm font-medium text-gray-800 shadow-2xs hover:bg-gray-50 focus:bg-gray-100 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 lg:hidden dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
        >
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
                <path d="m9 18 6-6-6-6" />
            </svg>
        </button>
    </Teleport>

    <div
        v-if="modal"
        :data-state="modal ? 'open' : 'closed'"
        class="data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 fixed inset-0 z-60 bg-gray-900/50 dark:bg-neutral-900/80"
    />

    <aside
        :data-state="modal ? 'open' : 'closed'"
        class="fixed inset-y-0 start-0 z-60 h-full w-65 transform border-e border-gray-200 bg-white transition-all duration-300 data-[state=open]:translate-x-0 lg:end-auto lg:bottom-0 lg:block lg:translate-x-0 ltr:-translate-x-full ltr:lg:-translate-x-0 rtl:translate-x-full rtl:lg:translate-x-0 dark:border-neutral-700 dark:bg-neutral-800"
    >
        <div class="flex h-full max-h-full flex-col py-3">
            <header class="px-5 text-center">
                <a
                    href="/"
                    class="inline-block flex-none rounded-md text-xl font-semibold focus:opacity-80 focus:outline-hidden"
                >
                    <svg class="h-14 w-full" viewBox="0 0 285 425" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0 227.925C18.3009 216.716 37.1986 205.908 55.6984 195V280.665C55.8973 299.679 62.4618 318.494 73.7009 333.705C86.5315 351.118 105.827 363.928 127.112 368.031C145.612 371.634 165.305 369.032 182.412 361.126C207.775 349.617 227.17 325.799 233.237 298.378C234.331 291.673 242.388 287.57 248.256 291.073C258.401 296.877 268.347 302.882 278.591 308.686C283.266 310.888 286.151 316.492 284.559 321.496C274.911 352.419 254.92 379.94 228.463 398.354C203.796 415.567 173.859 425.575 143.722 424.974C110.104 425.475 76.5853 413.165 50.5264 391.949C30.9325 375.337 15.9138 353.32 7.65853 329.002C2.58599 314.09 0.0994614 298.378 0 282.666V227.925Z"
                            fill="#1845E7"
                        />
                        <path
                            d="M135.073 237.287C146.381 235.165 158.396 238.095 167.584 244.967C178.892 253.153 185.656 267.099 184.95 281.045C184.344 299.033 171.016 315.808 153.55 320.255C144.16 322.579 133.862 321.973 124.977 317.931C112.76 312.372 103.37 300.953 100.745 287.714C99.1297 278.417 100.038 268.615 104.481 260.227C110.337 248.403 121.948 239.611 134.972 237.388H135.073V237.287Z"
                            fill="#1845E7"
                        />
                        <path
                            d="M273.366 0.944238C276.249 -0.288349 280.028 -0.596492 282.514 1.76597C284.901 3.92299 284.901 7.62075 285 10.5995V52.7129C285 56.4106 283.409 60.2111 280.326 62.2654C205.251 106.741 130.573 151.731 55.5965 196.412C37.1011 207.608 18.4067 218.496 0.0106998 230V79.3162C-0.188176 74.7967 2.39721 70.0718 6.77249 68.7365C18.5062 62.9845 30.4387 57.8487 42.073 51.8912C47.9398 49.0151 55.7954 54.0482 55.5965 60.9301V130.366C123.91 89.4851 192.324 48.8097 260.638 8.03162C264.914 5.66916 268.891 2.79312 273.366 0.944238Z"
                            fill="#0AC09E"
                        />
                    </svg>
                </a>
            </header>

            <div
                class="mt-1.5 h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 [&::-webkit-scrollbar-track]:bg-gray-100 dark:[&::-webkit-scrollbar-track]:bg-neutral-700"
            >
                <nav class="hs-accordion-group flex w-full flex-col flex-wrap pb-3">
                    <ul>
                        <li class="mb-1.5 px-5">
                            <Link
                                :href="route('dashboard.welcome')"
                                :class="{
                                    'bg-gray-100 dark:bg-neutral-700': route().current('dashboard.welcome'),
                                }"
                                class="flex gap-x-3 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                            >
                                <svg
                                    class="mt-0.5 size-4 shrink-0"
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
                                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                    <polyline points="9 22 9 12 15 12 15 22" />
                                </svg>
                                {{ __('dashboard') }}
                            </Link>
                        </li>

                        <li class="mb-1.5 px-5">
                            <Link
                                :href="route('dashboard.admins.index')"
                                :class="{
                                    'bg-gray-100 dark:bg-neutral-700': route().current('dashboard.admins.index'),
                                }"
                                class="flex gap-x-3 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                            >
                                <svg
                                    class="mt-0.5 size-4 shrink-0"
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
                                    <path
                                        d="M12 14.0466C9.7927 13.8404 7.53058 14.3187 5.57757 15.4816C4.1628 16.324 0.453365 18.0441 2.71266 20.1966C3.81631 21.248 5.04549 22 6.59087 22H13"
                                    />
                                    <path
                                        d="M15.5 6.5C15.5 8.98528 13.4853 11 11 11C8.51472 11 6.5 8.98528 6.5 6.5C6.5 4.01472 8.51472 2 11 2C13.4853 2 15.5 4.01472 15.5 6.5Z"
                                    />
                                    <path
                                        d="M22 17.5V15C20 15 18.5 14 18.5 14C18.5 14 17 15 15 15V17.5C15 21 18.5 22 18.5 22C18.5 22 22 21 22 17.5Z"
                                    />
                                </svg>
                                {{ __('resources.admin.plural') }}
                            </Link>
                        </li>

                        <li class="mb-1.5 px-5">
                            <Link
                                :href="route('dashboard.users.index')"
                                :class="{
                                    'bg-gray-100 dark:bg-neutral-700': route().current('dashboard.users.index'),
                                }"
                                class="flex gap-x-3 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100 focus:outline-hidden dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                            >
                                <svg
                                    class="mt-0.5 size-4 shrink-0"
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
                                    <path
                                        d="M15 8C15 9.65685 13.6569 11 12 11C10.3431 11 9 9.65685 9 8C9 6.34315 10.3431 5 12 5C13.6569 5 15 6.34315 15 8Z"
                                    />
                                    <path
                                        d="M16 4C17.6568 4 19 5.34315 19 7C19 8.22309 18.268 9.27523 17.2183 9.7423"
                                    />
                                    <path
                                        d="M13.7143 14H10.2857C7.91876 14 5.99998 15.9188 5.99998 18.2857C5.99998 19.2325 6.76749 20 7.71426 20H16.2857C17.2325 20 18 19.2325 18 18.2857C18 15.9188 16.0812 14 13.7143 14Z"
                                    />
                                    <path
                                        d="M17.7143 13C20.0812 13 22 14.9188 22 17.2857C22 18.2325 21.2325 19 20.2857 19"
                                    />
                                    <path d="M8 4C6.34315 4 5 5.34315 5 7C5 8.22309 5.73193 9.27523 6.78168 9.7423" />
                                    <path
                                        d="M3.71429 19C2.76751 19 2 18.2325 2 17.2857C2 14.9188 3.91878 13 6.28571 13"
                                    />
                                </svg>
                                {{ __('resources.user.plural') }}
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>

            <div
                :data-state="modal ? 'open' : 'closed'"
                class="absolute -end-3 top-3 z-10 data-[state=closed]:hidden lg:hidden"
            >
                <button
                    @click="close"
                    type="button"
                    class="inline-flex h-7 w-6 cursor-pointer items-center justify-center gap-x-2 rounded-md border border-gray-200 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:bg-gray-100 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                >
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
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                </button>
            </div>
        </div>
    </aside>
</template>
