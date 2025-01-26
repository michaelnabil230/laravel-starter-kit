<script setup lang="ts">
import useLocalization from '@/dashboard/composables/useLocalization';
import { App } from '@/dashboard/types/app';
import axios from 'axios';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import InputError from './../InputError.vue';
import Media from './Media.vue';

const { __ } = useLocalization();

interface UploadResult {
    file: File;
    response?: {
        media: App.Models.Media;
    };
    error?: unknown;
}

interface AxiosError {
    response?: {
        data?: {
            message?: string;
        };
    };
    message?: string;
}

const dropZone = ref<HTMLElement | null>(null);

const props = withDefaults(
    defineProps<{
        id: string;
        defaultValue?: App.Models.Media | App.Models.Media[] | null;
        multiple?: boolean;
        maxItems?: number;
        maxSize?: number;
        acceptedFileTypes?: string[];
    }>(),
    {
        multiple: false,
        maxItems: 1,
        maxSize: 5 * 1024 * 1024, // 5MB default
        acceptedFileTypes: () => ['*'],
    },
);

const model = defineModel<App.Models.Media | App.Models.Media[] | null>();

if (props.defaultValue !== undefined) {
    model.value = props.defaultValue;
}

const isDragging = ref(false);
const uploadInfo = ref<Record<string, number>>({});
const fileInput = ref<HTMLInputElement | null>(null);
const isUploading = ref(false);
const error = ref<string | null>(null);

onMounted(() => {
    document.addEventListener('dragenter', handleDragEnter);
    document.addEventListener('dragleave', handleDragLeave);
    document.addEventListener('dragover', handleDragOver);
    document.addEventListener('drop', handleDrop);
});

onBeforeUnmount(() => {
    document.removeEventListener('dragenter', handleDragEnter);
    document.removeEventListener('dragleave', handleDragLeave);
    document.removeEventListener('dragover', handleDragOver);
    document.removeEventListener('drop', handleDrop);
});

const handleDragEnter = (e: DragEvent) => {
    e.preventDefault();
    isDragging.value = true;
};

const handleDragLeave = (e: DragEvent) => {
    e.preventDefault();
    if (!dropZone.value?.contains(e.relatedTarget as Node)) {
        isDragging.value = false;
    }
};

const handleDragOver = (e: DragEvent) => {
    e.preventDefault();
    if (dropZone.value?.contains(e.target as Node)) {
        isDragging.value = true;
    }
};

const handleDrop = (event: DragEvent) => {
    event.preventDefault();
    isDragging.value = false;

    if (!event.dataTransfer?.files.length) return;

    const droppedFiles = Array.from(event.dataTransfer.files);
    processFiles(droppedFiles);
};

const handleFileSelect = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files) {
        processFiles(Array.from(input.files));
    }
};

const uploadFile = async (file: File) => {
    uploadInfo.value[file.name] = 0;

    const formData = new FormData();
    formData.append('file', file);

    const response = await axios.post(route('api.file-upload'), formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
        onUploadProgress: (progressEvent) => {
            if (progressEvent.total) {
                const progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                uploadInfo.value[file.name] = progress;
            }
        },
    });

    // Remove upload info after completion
    delete uploadInfo.value[file.name];

    return response.data;
};

const uploadFiles = async (files: File[]) => {
    if (isUploading.value) return;
    if (totalUploadProgress.value !== 0) return;

    isUploading.value = true;
    error.value = null;

    try {
        const uploadPromises = files.map(async (file) => {
            try {
                const response = await uploadFile(file);
                return { response } as UploadResult;
            } catch (err) {
                const axiosFileUploadError = err as AxiosError;
                const errorObject = err as Error;

                error.value =
                    axiosFileUploadError.response?.data?.message ??
                    errorObject.message ??
                    __('global.file_upload.upload_failed');

                return { error: err } as UploadResult;
            }
        });

        const results = await Promise.all(uploadPromises);

        // Get successful uploads with their responses
        const successfulUploads = results.filter((result: UploadResult) => !result.error);

        // Update model value with successful uploads' API responses
        if (props.maxItems === 1) {
            model.value = successfulUploads[0]?.response?.media ?? null;
        } else {
            // Get the new media items from successful uploads
            const newMediaItems = successfulUploads.map((upload: UploadResult) => upload.response!.media);

            // If there's an existing model value, append the new items
            if (Array.isArray(model.value)) {
                model.value = [...model.value, ...newMediaItems];
            } else {
                model.value = newMediaItems;
            }
        }
    } finally {
        isUploading.value = false;
        uploadInfo.value = {};
    }
};

const processFiles = async (newFiles: File[]) => {
    error.value = null;

    // Validate file count
    if (!props.multiple && newFiles.length > 1) {
        error.value = __('global.file_upload.single_file_only');
        return;
    }

    const uploadedFilesLength = Array.isArray(model.value) ? model.value.length : model.value == null ? 0 : 1;

    if (uploadedFilesLength + newFiles.length > props.maxItems) {
        error.value = __('global.file_upload.max_files_exceeded', { count: props.maxItems.toString() });
        return;
    }

    // Validate file size and type
    const validFiles = newFiles.filter((file) => {
        if (file.size > props.maxSize) {
            error.value = __('global.file_upload.file_too_large', {
                name: file.name,
                size: (props.maxSize / (1024 * 1024)).toString(),
            });
            return false;
        }

        if (
            props.acceptedFileTypes[0] !== '*' &&
            !props.acceptedFileTypes.some((type) => {
                if (type.endsWith('*')) {
                    return file.type.startsWith(type.replace('*', ''));
                }
                return file.type === type || file.type.split('/')[1] === type;
            })
        ) {
            error.value = __('global.file_upload.invalid_file_type', { type: file.type });
            return false;
        }

        return true;
    });

    // Start upload if we have valid files
    if (validFiles.length > 0) {
        await uploadFiles(validFiles);
    }
};

const handleClick = () => {
    fileInput.value?.click();
};

const removeFile = (id: number) => {
    if (Array.isArray(model.value)) {
        model.value = model.value.filter((media: App.Models.Media) => media.id !== id);
    } else {
        model.value = null;
    }
};

const totalUploadProgress = computed(() => {
    const values = Object.values(uploadInfo.value);
    if (values.length === 0) return 0;
    const sum = values.reduce((acc, progress) => acc + progress, 0);
    return Math.round(sum / values.length);
});
</script>

<template>
    <div>
        <div
            ref="dropZone"
            @dragenter="handleDragEnter"
            @dragleave="handleDragLeave"
            @dragover="handleDragOver"
            @drop="handleDrop"
            @click="handleClick"
        >
            <div
                class="flex cursor-pointer justify-center rounded-xl border border-dashed bg-white p-12 dark:bg-neutral-800"
                :class="{
                    'border-gray-300 dark:border-neutral-600': !isDragging && !error,
                    'border-blue-500 bg-blue-50! focus:border-blue-500 focus:ring-blue-500 dark:border-blue-500 dark:bg-blue-900/20! dark:focus:border-blue-500 dark:focus:ring-blue-500':
                        isDragging,
                    'border-red-500 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:focus:border-red-500 dark:focus:ring-red-500':
                        error,
                }"
            >
                <div v-if="totalUploadProgress == 0" class="text-center">
                    <span
                        class="inline-flex size-16 items-center justify-center rounded-full bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-200"
                    >
                        <svg
                            class="size-6 shrink-0"
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
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" x2="12" y1="3" y2="15"></line>
                        </svg>
                    </span>

                    <div class="mt-4 flex flex-wrap justify-center text-sm/6 text-gray-600">
                        <span class="pe-1 font-medium text-gray-800 dark:text-neutral-200">
                            {{ __('global.file_upload.drop_here_or') }}
                        </span>
                        <span
                            class="cursor-pointer rounded-lg bg-white font-semibold text-blue-600 decoration-2 focus-within:ring-2 focus-within:ring-blue-600 focus-within:ring-offset-2 focus-within:outline-hidden hover:text-blue-700 hover:underline dark:bg-neutral-800 dark:text-blue-500 dark:hover:text-blue-600"
                        >
                            {{ __('global.file_upload.browse') }}
                        </span>
                    </div>

                    <div class="mt-1 text-xs text-gray-400 dark:text-neutral-400">
                        <p>{{ __('global.file_upload.max_size', { size: (maxSize / (1024 * 1024)).toString() }) }}</p>
                        <template v-if="acceptedFileTypes[0] !== '*'">
                            <p>{{ __('global.file_upload.allowed_types', { types: acceptedFileTypes.join(', ') }) }}</p>
                        </template>
                    </div>
                </div>
                <div v-else class="flex w-full items-center gap-x-3 whitespace-nowrap">
                    <div class="flex h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-neutral-700">
                        <div
                            class="flex flex-col justify-center overflow-hidden rounded-full bg-blue-600 text-center text-xs whitespace-nowrap text-white transition-all duration-500"
                            :style="{ width: `${totalUploadProgress}%` }"
                        ></div>
                    </div>
                    <span class="text-end text-sm text-gray-800 dark:text-white">
                        {{ `${totalUploadProgress}%` }}
                    </span>
                </div>
            </div>
        </div>

        <InputError v-if="error" :message="error" />

        <input
            :id="id"
            ref="fileInput"
            type="file"
            class="hidden"
            :multiple="multiple"
            :accept="acceptedFileTypes.join(',')"
            @change="handleFileSelect"
            :disabled="isUploading"
        />

        <Media :medias="model ?? null" :removeFile="removeFile" />
    </div>
</template>
