<script setup lang="ts">
import { App } from '@/dashboard/types/app';

const props = defineProps<{
    media: App.Models.Media;
    removeFile?: (id: number) => void;
}>();

const download = () => {
    const url = new URL(props.media.url);
    const a = document.createElement('a');

    a.href = url.toString();
    a.download = props.media.file_name;

    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
};
</script>

<template>
    <div
        class="mb-1 flex items-center justify-between rounded-xl border border-solid border-gray-300 bg-white p-3 dark:border-neutral-600 dark:bg-neutral-800"
    >
        <div class="flex items-center gap-x-3">
            <span
                class="flex size-10 items-center justify-center rounded-lg border border-gray-200 stroke-white text-gray-500 dark:border-neutral-700 dark:text-neutral-500"
            >
                <img v-if="media.preview" :src="media.preview" class="size-full rounded-lg object-cover" />
                <svg
                    v-if="media.mime_type === 'application/pdf'"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path
                        d="M20 13V10.6569C20 9.83935 20 9.4306 19.8478 9.06306C19.6955 8.69552 19.4065 8.40649 18.8284 7.82843L14.0919 3.09188C13.593 2.593 13.3436 2.34355 13.0345 2.19575C12.9702 2.165 12.9044 2.13772 12.8372 2.11401C12.5141 2 12.1614 2 11.4558 2C8.21082 2 6.58831 2 5.48933 2.88607C5.26731 3.06508 5.06508 3.26731 4.88607 3.48933C4 4.58831 4 6.21082 4 9.45584V13M13 2.5V3C13 5.82843 13 7.24264 13.8787 8.12132C14.7574 9 16.1716 9 19 9H19.5"
                    ></path>
                    <path
                        d="M19.75 16H17.25C16.6977 16 16.25 16.4477 16.25 17V19M16.25 19V22M16.25 19H19.25M4.25 22V19.5M4.25 19.5V16H6C6.9665 16 7.75 16.7835 7.75 17.75C7.75 18.7165 6.9665 19.5 6 19.5H4.25ZM10.25 16H11.75C12.8546 16 13.75 16.8954 13.75 18V20C13.75 21.1046 12.8546 22 11.75 22H10.25V16Z"
                    ></path>
                </svg>
                <svg
                    v-if="media.mime_type.startsWith('video/')"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path
                        d="M14 5C17.7712 5 19.6569 5 20.8284 6.2448C22 7.48959 22 9.49306 22 13.5C22 17.5069 22 19.5104 20.8284 20.7552C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7552C2 19.5104 2 17.5069 2 13.5C2 9.49306 2 7.48959 3.17157 6.2448C4.34315 5 6.22876 5 10 5L14 5Z"
                    ></path>
                    <path
                        d="M17.3965 11.2504C18.6389 13.4023 17.9016 16.154 15.7496 17.3965C13.5977 18.6389 10.846 17.9016 9.60354 15.7496M17.3965 11.2504C16.154 9.09842 13.4023 8.3611 11.2504 9.60354C9.09842 10.846 8.3611 13.5977 9.60354 15.7496M17.3965 11.2504L9.60354 15.7496"
                    ></path>
                    <path d="M17 2L7 5"></path>
                    <path d="M6 9H6.00898"></path>
                </svg>
                <svg
                    v-if="media.mime_type === 'text/csv'"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path
                        d="M7.5 17.2196C7.44458 16.0292 6.62155 16 5.50505 16C3.78514 16 3.5 16.406 3.5 18V20C3.5 21.594 3.78514 22 5.50505 22C6.62154 22 7.44458 21.9708 7.5 20.7804M20.5 16L18.7229 20.6947C18.3935 21.5649 18.2288 22 17.968 22C17.7071 22 17.5424 21.5649 17.213 20.6947L15.4359 16M12.876 16H11.6951C11.2231 16 10.9872 16 10.8011 16.0761C10.1672 16.3354 10.1758 16.9448 10.1758 17.5C10.1758 18.0553 10.1672 18.6647 10.8011 18.9239C10.9872 19 11.2232 19 11.6951 19C12.167 19 12.4029 19 12.5891 19.0761C13.2229 19.3354 13.2143 19.9447 13.2143 20.5C13.2143 21.0553 13.2229 21.6647 12.5891 21.9239C12.4029 22 12.167 22 11.6951 22H10.4089"
                    ></path>
                    <path
                        d="M20 13V10.6569C20 9.83935 20 9.4306 19.8478 9.06306C19.6955 8.69552 19.4065 8.40649 18.8284 7.82843L14.0919 3.09188C13.593 2.593 13.3436 2.34355 13.0345 2.19575C12.9702 2.165 12.9044 2.13772 12.8372 2.11401C12.5141 2 12.1614 2 11.4558 2C8.21082 2 6.58831 2 5.48933 2.88607C5.26731 3.06508 5.06508 3.26731 4.88607 3.48933C4 4.58831 4 6.21082 4 9.45584V13M13 2.5V3C13 5.82843 13 7.24264 13.8787 8.12132C14.7574 9 16.1716 9 19 9H19.5"
                    ></path>
                </svg>
            </span>
            <div>
                <p class="text-sm font-medium text-gray-800 dark:text-white">
                    {{ media.file_name }}
                </p>
                <p class="text-xs text-gray-500 dark:text-neutral-500">
                    {{ media.human_readable_size }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-x-2">
            <button
                type="button"
                v-if="removeFile"
                @click="removeFile(media.id)"
                class="cursor-pointer text-gray-500 hover:text-gray-800 focus:text-gray-800 focus:outline-hidden dark:text-neutral-500 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
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
                    <path d="M3 6h18"></path>
                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                    <line x1="10" x2="10" y1="11" y2="17"></line>
                    <line x1="14" x2="14" y1="11" y2="17"></line>
                </svg>
            </button>
            <button
                type="button"
                @click="download"
                class="cursor-pointer text-gray-500 hover:text-gray-800 focus:text-gray-800 focus:outline-hidden dark:text-neutral-500 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
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
                        d="M12 14.5L12 4.5M12 14.5C11.2998 14.5 9.99153 12.5057 9.5 12M12 14.5C12.7002 14.5 14.0085 12.5057 14.5 12"
                    ></path>
                    <path d="M20 16.5C20 18.982 19.482 19.5 17 19.5H7C4.518 19.5 4 18.982 4 16.5"></path>
                </svg>
            </button>
        </div>
    </div>
</template>
