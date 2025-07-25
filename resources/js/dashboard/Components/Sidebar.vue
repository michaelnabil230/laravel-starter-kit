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
        class="fixed inset-y-0 start-0 z-60 h-full w-65 transform border-e border-gray-200 bg-white transition-transform duration-300 data-[state=open]:translate-x-0 lg:end-auto lg:bottom-0 lg:block lg:translate-x-0 ltr:-translate-x-full ltr:lg:-translate-x-0 rtl:translate-x-full rtl:lg:translate-x-0 dark:border-neutral-700 dark:bg-neutral-800"
    >
        <div class="flex h-full max-h-full flex-col py-3">
            <header class="px-5 text-center">
                <a
                    href="/"
                    class="inline-block flex-none rounded-md text-xl font-semibold focus:opacity-80 focus:outline-hidden"
                >
                    <svg class="h-14 w-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 934 345">
                        <g>
                            <polygon
                                fill="#ff2d20"
                                points="389.647,121.376 373.181,121.376 373.181,221.606 420.575,221.606 420.575,206.858 389.647,206.858"
                            />
                            <path
                                fill="#ff2d20"
                                d="M478.706,163.902c-2.101-3.341-5.083-5.965-8.949-7.875c-3.865-1.909-7.756-2.864-11.669-2.864 c-5.062,0-9.69,0.931-13.89,2.792c-4.201,1.861-7.804,4.417-10.811,7.661c-3.007,3.246-5.347,6.993-7.016,11.239c-1.672,4.249-2.506,8.713-2.506,13.389c0,4.774,0.834,9.26,2.506,13.459c1.669,4.202,4.009,7.925,7.016,11.169 c3.007,3.246,6.609,5.799,10.811,7.66c4.199,1.861,8.828,2.792,13.89,2.792c3.913,0,7.804-0.955,11.669-2.863 c3.866-1.908,6.849-4.533,8.949-7.875v9.021h15.607v-66.725h-15.607V163.902z M477.275,196.405 c-0.955,2.578-2.291,4.821-4.009,6.73c-1.719,1.91-3.795,3.437-6.229,4.582c-2.435,1.146-5.133,1.718-8.091,1.718 c-2.96,0-5.633-0.572-8.019-1.718c-2.387-1.146-4.438-2.672-6.156-4.582c-1.719-1.909-3.032-4.152-3.938-6.73 c-0.909-2.577-1.36-5.298-1.36-8.161c0-2.864,0.451-5.585,1.36-8.162c0.905-2.577,2.219-4.819,3.938-6.729 c1.718-1.908,3.77-3.437,6.156-4.582c2.386-1.146,5.059-1.718,8.019-1.718c2.958,0,5.656,0.572,8.091,1.718 c2.434,1.146,4.51,2.674,6.229,4.582c1.718,1.91,3.054,4.152,4.009,6.729c0.953,2.577,1.432,5.298,1.432,8.162 C478.706,191.107,478.228,193.828,477.275,196.405z"
                            />
                            <path
                                fill="#ff2d20"
                                d="M605.569,163.902c-2.101-3.341-5.083-5.965-8.949-7.875c-3.865-1.909-7.756-2.864-11.669-2.864 c-5.062,0-9.69,0.931-13.89,2.792c-4.201,1.861-7.804,4.417-10.811,7.661c-3.007,3.246-5.347,6.993-7.016,11.239 c-1.672,4.249-2.506,8.713-2.506,13.389c0,4.774,0.834,9.26,2.506,13.459c1.669,4.202,4.009,7.925,7.016,11.169 c3.007,3.246,6.609,5.799,10.811,7.66c4.199,1.861,8.828,2.792,13.89,2.792c3.913,0,7.804-0.955,11.669-2.863 c3.866-1.908,6.849-4.533,8.949-7.875v9.021h15.607v-66.725h-15.607V163.902z M604.137,196.405 c-0.955,2.578-2.291,4.821-4.009,6.73c-1.719,1.91-3.795,3.437-6.229,4.582c-2.435,1.146-5.133,1.718-8.091,1.718 c-2.96,0-5.633-0.572-8.019-1.718c-2.387-1.146-4.438-2.672-6.156-4.582c-1.719-1.909-3.032-4.152-3.938-6.73 c-0.909-2.577-1.36-5.298-1.36-8.161c0-2.864,0.451-5.585,1.36-8.162c0.905-2.577,2.219-4.819,3.938-6.729 c1.718-1.908,3.77-3.437,6.156-4.582c2.386-1.146,5.059-1.718,8.019-1.718c2.958,0,5.656,0.572,8.091,1.718 c2.434,1.146,4.51,2.674,6.229,4.582c1.718,1.91,3.054,4.152,4.009,6.729c0.953,2.577,1.432,5.298,1.432,8.162 C605.569,191.107,605.09,193.828,604.137,196.405z"
                            />
                            <rect x="776.781" y="121.376" fill="#ff2d20" width="15.606" height="100.23" />
                            <polygon
                                fill="#ff2d20"
                                points="506.628,221.606 522.235,221.606 522.235,170.238 549.011,170.238 549.011,154.882 506.628,154.882"
                            />
                            <polygon
                                fill="#ff2d20"
                                points="683.748,154.882 664.132,205.978 644.516,154.882 628.708,154.882 654.325,221.606 673.939,221.606 699.556,154.882"
                            />
                            <path
                                fill="#ff2d20"
                                d="M733.595,153.166c-19.112,0-34.239,15.706-34.239,35.079c0,21.416,14.641,35.079,36.239,35.079 c12.088,0,19.806-4.622,29.234-14.688l-10.544-8.158c-0.006,0.008-7.958,10.449-19.832,10.449 c-13.802,0-19.612-11.127-19.612-16.884h51.777C769.338,172,754.846,153.166,733.595,153.166z M714.882,182.446 c0.12-1.284,1.917-16.884,18.589-16.884c16.671,0,18.697,15.598,18.813,16.884H714.882z"
                            />
                            <path
                                fill="#ff2d20"
                                d="M325.683,120.592c-0.024-0.088-0.073-0.165-0.104-0.25c-0.058-0.157-0.108-0.316-0.191-0.46 c-0.056-0.097-0.137-0.176-0.203-0.265c-0.087-0.117-0.161-0.242-0.265-0.345c-0.085-0.086-0.194-0.148-0.29-0.223 c-0.109-0.085-0.206-0.182-0.327-0.252c0,0-0.001,0-0.002-0.001c-0.001,0-0.001-0.001-0.002-0.002L288.651,98.27 c-0.917-0.528-2.047-0.528-2.964,0l-35.647,20.522c-0.001,0-0.001,0.001-0.002,0.002c-0.001,0-0.002,0-0.002,0.001 c-0.121,0.07-0.219,0.167-0.327,0.252c-0.096,0.075-0.205,0.138-0.29,0.223c-0.103,0.103-0.178,0.228-0.265,0.345 c-0.066,0.089-0.147,0.169-0.203,0.265c-0.083,0.144-0.133,0.304-0.191,0.46c-0.031,0.085-0.08,0.162-0.104,0.25 c-0.067,0.249-0.103,0.51-0.103,0.776v38.979l-29.706,17.103v-76.255c0-0.265-0.036-0.526-0.103-0.776 c-0.024-0.088-0.073-0.165-0.104-0.25c-0.058-0.157-0.108-0.316-0.191-0.46c-0.056-0.097-0.137-0.176-0.203-0.265 c-0.087-0.117-0.161-0.242-0.265-0.345c-0.085-0.086-0.194-0.148-0.29-0.223c-0.109-0.085-0.206-0.182-0.327-0.252 c0,0-0.001,0-0.002-0.001c-0.001,0-0.001-0.001-0.002-0.002l-35.647-20.523c-0.917-0.528-2.047-0.528-2.964,0l-35.647,20.523 c-0.001,0-0.001,0.001-0.002,0.002c-0.001,0-0.002,0-0.002,0.001c-0.121,0.07-0.219,0.167-0.327,0.252 c-0.096,0.075-0.205,0.138-0.29,0.223c-0.103,0.103-0.178,0.228-0.265,0.345c-0.066,0.089-0.147,0.169-0.203,0.265 c-0.083,0.144-0.133,0.304-0.191,0.46c-0.031,0.085-0.08,0.162-0.104,0.25c-0.067,0.249-0.103,0.51-0.103,0.776v122.09 c0,1.063,0.568,2.044,1.489,2.575l71.293,41.045c0.156,0.089,0.324,0.143,0.49,0.202c0.078,0.028,0.15,0.074,0.23,0.095 c0.249,0.066,0.506,0.1,0.762,0.1c0.256,0,0.512-0.033,0.762-0.1c0.069-0.018,0.132-0.059,0.2-0.083 c0.176-0.061,0.354-0.119,0.519-0.214l71.293-41.045c0.921-0.53,1.489-1.512,1.489-2.575v-38.979l34.158-19.666 c0.921-0.53,1.489-1.512,1.489-2.575v-40.697C325.786,121.102,325.751,120.841,325.683,120.592z M215.87,219.867l-29.648-16.779 l31.136-17.926c0,0,0,0,0.001-0.001l34.164-19.669l29.674,17.084l-21.772,12.428L215.87,219.867z M284.199,143.608v33.841 l-12.475-7.182l-17.231-9.92v-33.841l12.475,7.182L284.199,143.608z M287.169,104.273l29.693,17.095l-29.693,17.095l-29.693-17.095 L287.169,104.273z M195.675,190.789l-12.475,7.182v-74.538l17.231-9.92l12.475-7.182v74.537L195.675,190.789z M180.229,84.098 l29.693,17.095l-29.693,17.095l-29.693-17.095L180.229,84.098z M147.553,106.332l12.475,7.182l17.231,9.92v79.676 c0,0.002,0.001,0.003,0.001,0.005s-0.001,0.004-0.001,0.006c0,0.114,0.032,0.221,0.045,0.333c0.017,0.146,0.021,0.294,0.059,0.434 c0.001,0.002,0.001,0.005,0.002,0.007c0.032,0.117,0.094,0.222,0.14,0.334c0.051,0.124,0.088,0.255,0.156,0.371 c0.001,0.003,0.002,0.006,0.004,0.009c0.061,0.105,0.149,0.191,0.222,0.288c0.081,0.105,0.149,0.22,0.244,0.314 c0.003,0.003,0.005,0.007,0.008,0.01c0.084,0.083,0.19,0.142,0.284,0.215c0.106,0.083,0.202,0.178,0.32,0.247 c0.004,0.002,0.009,0.003,0.013,0.005c0.004,0.002,0.007,0.006,0.011,0.008l34.139,19.321v34.175l-65.352-37.625V106.332z M284.199,221.567l-65.352,37.625v-34.182l48.399-27.628l16.953-9.677V221.567z M319.845,160.347l-29.706,17.102v-33.841 l17.231-9.92l12.475-7.182V160.347z"
                            />
                        </g>
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
