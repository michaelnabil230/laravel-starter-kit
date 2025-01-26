<script setup lang="ts">
import useLocalization from '@/dashboard/composables/useLocalization';
import AppLayout from '@/dashboard/Layouts/AppLayout.vue';
import { App } from '@/dashboard/types/app';

const { __ } = useLocalization();

const breadcrumbs: Breadcrumbs = [
    { text: __('dashboard'), href: route('dashboard.welcome') },
    {
        text: __('resources.user.plural'),
        href: route('dashboard.users.index'),
    },
    { text: __('global.crud.show') },
];

defineProps<{
    user: App.Models.User;
}>();
</script>

<template>
    <AppLayout
        :title="
            __('global.resource.show', {
                resource: __('resources.user.singular'),
            })
        "
        :breadcrumbs="breadcrumbs"
    >
        <!-- User Profile Card -->
        <div
            class="rounded-xl border border-stone-200 bg-white px-5 py-3 shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
        >
            <div class="flex flex-col items-center gap-3 md:flex-row md:items-start">
                <img class="size-30 rounded-xl object-cover" :src="user.profile_photo_url" alt="Avatar" />

                <div class="flex w-full flex-col gap-1.5">
                    <h1 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        {{ user.name }}
                    </h1>

                    <div class="grid gap-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <a
                            :href="'mailto:' + user.email"
                            class="inline-flex items-center gap-x-2 text-sm text-gray-500 decoration-2 hover:underline focus:underline dark:text-neutral-500"
                        >
                            <svg
                                class="size-4 shrink-0"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M2 6L8.91302 9.91697C11.4616 11.361 12.5384 11.361 15.087 9.91697L22 6" />
                                <path
                                    d="M2.01577 13.4756C2.08114 16.5412 2.11383 18.0739 3.24496 19.2094C4.37608 20.3448 5.95033 20.3843 9.09883 20.4634C11.0393 20.5122 12.9607 20.5122 14.9012 20.4634C18.0497 20.3843 19.6239 20.3448 20.7551 19.2094C21.8862 18.0739 21.9189 16.5412 21.9842 13.4756C22.0053 12.4899 22.0053 11.5101 21.9842 10.5244C21.9189 7.45886 21.8862 5.92609 20.7551 4.79066C19.6239 3.65523 18.0497 3.61568 14.9012 3.53657C12.9607 3.48781 11.0393 3.48781 9.09882 3.53656C5.95033 3.61566 4.37608 3.65521 3.24495 4.79065C2.11382 5.92608 2.08114 7.45885 2.01576 10.5244C1.99474 11.5101 1.99475 12.4899 2.01577 13.4756Z"
                                />
                            </svg>
                            {{ user.email }}
                        </a>

                        <a
                            :href="'tel:' + user.phone"
                            class="inline-flex items-center gap-x-2 text-sm text-gray-500 decoration-2 hover:underline focus:underline dark:text-neutral-500"
                        >
                            <svg
                                class="size-4 shrink-0"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M9.1585 5.71223L8.75584 4.80625C8.49256 4.21388 8.36092 3.91768 8.16405 3.69101C7.91732 3.40694 7.59571 3.19794 7.23592 3.08785C6.94883 3 6.6247 3 5.97645 3C5.02815 3 4.554 3 4.15597 3.18229C3.68711 3.39702 3.26368 3.86328 3.09497 4.3506C2.95175 4.76429 2.99278 5.18943 3.07482 6.0397C3.94815 15.0902 8.91006 20.0521 17.9605 20.9254C18.8108 21.0075 19.236 21.0485 19.6496 20.9053C20.137 20.7366 20.6032 20.3131 20.818 19.8443C21.0002 19.4462 21.0002 18.9721 21.0002 18.0238C21.0002 17.3755 21.0002 17.0514 20.9124 16.7643C20.8023 16.4045 20.5933 16.0829 20.3092 15.8362C20.0826 15.6393 19.7864 15.5077 19.194 15.2444L18.288 14.8417C17.6465 14.5566 17.3257 14.4141 16.9998 14.3831C16.6878 14.3534 16.3733 14.3972 16.0813 14.5109C15.7762 14.6297 15.5066 14.8544 14.9672 15.3038C14.4304 15.7512 14.162 15.9749 13.834 16.0947C13.5432 16.2009 13.1588 16.2403 12.8526 16.1951C12.5071 16.1442 12.2426 16.0029 11.7135 15.7201C10.0675 14.8405 9.15977 13.9328 8.28011 12.2867C7.99738 11.7577 7.85602 11.4931 7.80511 11.1477C7.75998 10.8414 7.79932 10.457 7.90554 10.1663C8.02536 9.83828 8.24905 9.56986 8.69643 9.033C9.14586 8.49368 9.37058 8.22402 9.48939 7.91891C9.60309 7.62694 9.64686 7.3124 9.61719 7.00048C9.58618 6.67452 9.44362 6.35376 9.1585 5.71223Z"
                                />
                            </svg>
                            <span dir="ltr">{{ user.phone }}</span>
                        </a>

                        <p class="inline-flex items-center gap-x-2 text-sm text-gray-500 dark:text-neutral-500">
                            <svg
                                class="size-4 shrink-0"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M13.5 4.5C13.5 5.32843 12.8284 6 12 6C11.1716 6 10.5 5.32843 10.5 4.5C10.5 3.67157 12 2 12 2C12 2 13.5 3.67157 13.5 4.5Z"
                                />
                                <path d="M12 6V9" />
                                <path
                                    d="M17.6667 14C19.2315 14 20.5 12.8807 20.5 11.5C20.5 10.1193 19.2315 9 17.6667 9H6.33333C4.76853 9 3.5 10.1193 3.5 11.5C3.5 12.8807 4.76853 14 6.33333 14C7.70408 14 8.90415 13.1411 9.16667 12C9.42919 13.1411 10.6293 14 12 14C13.3707 14 14.5708 13.1411 14.8333 12C15.0959 13.1411 16.2959 14 17.6667 14Z"
                                />
                                <path
                                    d="M5 14L5.52089 16.5796C6.04532 19.1768 6.30754 20.4754 7.19608 21.2377C8.08462 22 9.33608 22 11.839 22H12.161C14.6639 22 15.9154 22 16.8039 21.2377C17.6925 20.4754 17.9547 19.1768 18.4791 16.5796L19 14"
                                />
                            </svg>
                            {{ user.birth_date }}
                        </p>

                        <p class="inline-flex items-center gap-x-2 text-sm text-gray-500 dark:text-neutral-500">
                            <svg
                                v-if="user.gender == App.Enums.Gender.MALE"
                                class="size-4 shrink-0"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M21 9C21 12.3137 18.3137 15 15 15C11.6863 15 9 12.3137 9 9C9 5.68629 11.6863 3 15 3C18.3137 3 21 5.68629 21 9Z"
                                />
                                <path
                                    d="M3 15V17C3 18.8856 3 19.8284 3.58579 20.4142C4.17157 21 5.11438 21 7 21H9M4 20L10.5 13.5"
                                />
                            </svg>
                            <svg
                                v-if="user.gender == App.Enums.Gender.FEMALE"
                                class="size-4 shrink-0"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M12 14C15.3137 14 18 11.3137 18 8C18 4.68629 15.3137 2 12 2C8.68629 2 6 4.68629 6 8C6 11.3137 8.68629 14 12 14ZM12 14V22M9 19H15"
                                />
                            </svg>

                            {{ __('global.enums.gender.' + user.gender) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End User Profile Card -->
    </AppLayout>
</template>
