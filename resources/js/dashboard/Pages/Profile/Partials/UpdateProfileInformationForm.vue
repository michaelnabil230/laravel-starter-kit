<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import InputError from '@/dashboard/Components/Inputs/InputError.vue';
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import PhoneInput from '@/dashboard/Components/Inputs/PhoneInput.vue';
import TextInput from '@/dashboard/Components/Inputs/TextInput.vue';
import { useAuth } from '@/dashboard/composables/useAuth';
import useLocalization from '@/dashboard/composables/useLocalization';
import useToasts from '@/dashboard/composables/useToasts';
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { __ } = useLocalization();
const toasts = useToasts();

const auth = useAuth();

const photoPreview = ref<string | ArrayBuffer | null>(null);

const photoInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    _method: 'PUT',
    name: auth.value.admin.name,
    email: auth.value.admin.email,
    phone: auth.value.admin.phone,
    phone_country: auth.value.admin.phone_country,
    photo: null as File | null,
});

const submit = (): void => {
    if (photoInput.value?.files?.length) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('dashboard.profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            toasts.success(__('global.actions.updated', { resource: __('profile.name') }));
            clearPhotoFileInput();
        },
        onError: () => toasts.danger(__('errors.error_happened')),
    });
};

const selectNewPhoto = (): void => {
    photoInput.value?.click();
};

const updatePhotoPreview = (): void => {
    const photo = photoInput.value?.files?.[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (event: ProgressEvent<FileReader>) => {
        if (event.target?.result !== undefined) {
            photoPreview.value = event.target.result as string | ArrayBuffer;
        }
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = (): void => {
    router.delete(route('dashboard.profile.photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = (): void => {
    if (photoInput.value?.value) {
        photoInput.value.value = '';
    }
};
</script>

<template>
    <!-- Card -->
    <form
        @submit.prevent="submit()"
        class="flex flex-col rounded-xl border border-stone-200 bg-white shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
    >
        <!-- Header -->
        <div class="border-b border-stone-200 px-5 py-3 dark:border-neutral-700">
            <h2 class="inline-block font-semibold text-stone-800 dark:text-neutral-200">
                {{ __('profile.personal_info.title') }}
            </h2>
        </div>
        <!-- End Header -->

        <!-- Body -->
        <div class="space-y-4 p-5">
            <!-- Grid -->
            <div class="grid gap-y-1.5 sm:grid-cols-12 sm:gap-x-5 sm:gap-y-0">
                <div class="sm:col-span-4 xl:col-span-3 2xl:col-span-2">
                    <InputLabel for="photo" value="Photo" />
                </div>
                <!-- End Col -->

                <div class="sm:col-span-8 xl:col-span-4">
                    <!-- Upload Group -->
                    <div class="flex flex-wrap items-center gap-3 sm:gap-5">
                        <input
                            id="photo"
                            ref="photoInput"
                            type="file"
                            class="hidden"
                            @change="updatePhotoPreview"
                            accept="image/*"
                        />

                        <img
                            :src="photoPreview?.toString() ?? auth.admin.profile_photo_url"
                            :alt="auth.admin.name"
                            class="size-20 shrink-0 rounded-full object-cover"
                        />

                        <div class="grow">
                            <div class="flex items-center gap-x-2">
                                <Button @click.prevent="selectNewPhoto" class="text-xs! font-medium!">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" x2="12" y1="3" y2="15" />
                                    </svg>
                                    {{ __('upload_photo') }}
                                </Button>

                                <Button
                                    variant="text"
                                    color="danger"
                                    v-if="auth.admin.photo"
                                    @click.prevent="deletePhoto"
                                    :value="__('global.crud.delete')"
                                />
                            </div>
                        </div>

                        <InputError :message="form.errors.photo" />
                    </div>
                    <!-- End Upload Group -->
                </div>
                <!-- End Col -->
            </div>
            <!-- End Grid -->

            <!-- Grid -->
            <div class="grid gap-y-1.5 sm:grid-cols-12 sm:gap-x-5 sm:gap-y-0">
                <div class="sm:col-span-4 xl:col-span-3 2xl:col-span-2">
                    <InputLabel for="name" value="Name" />
                </div>
                <!-- End Col -->

                <div class="sm:col-span-8 xl:col-span-4">
                    <TextInput
                        v-model="form.name"
                        :hasError="form.errors.hasOwnProperty('name')"
                        id="name"
                        type="text"
                        :placeholder="__('profile.personal_info.placeholder.name')"
                        required
                        autofocus
                        autocomplete="name"
                    />

                    <InputError :message="form.errors.name" />
                </div>
                <!-- End Col -->
            </div>
            <!-- End Grid -->

            <!-- Grid -->
            <div class="grid gap-y-1.5 sm:grid-cols-12 sm:gap-x-5 sm:gap-y-0">
                <div class="sm:col-span-4 xl:col-span-3 2xl:col-span-2">
                    <InputLabel for="email" value="Email" />
                </div>
                <!-- End Col -->

                <div class="sm:col-span-8 xl:col-span-4">
                    <TextInput
                        v-model="form.email"
                        :hasError="form.errors.hasOwnProperty('email')"
                        id="email"
                        type="email"
                        :placeholder="__('profile.personal_info.placeholder.email')"
                        required
                        autocomplete="username"
                    />

                    <InputError :message="form.errors.email" />
                </div>
                <!-- End Col -->
            </div>
            <!-- End Grid -->

            <!-- Grid -->
            <div class="grid gap-y-1.5 sm:grid-cols-12 sm:gap-x-5 sm:gap-y-0">
                <div class="sm:col-span-4 xl:col-span-3 2xl:col-span-2">
                    <InputLabel for="phone" value="Phone" />
                </div>
                <!-- End Col -->

                <div class="sm:col-span-8 xl:col-span-4">
                    <PhoneInput
                        id="phone"
                        v-model:phone="form.phone"
                        v-model:phone_country="form.phone_country"
                        :placeholder="
                            __('global.placeholder', {
                                attribute: __('global.attributes.phone'),
                            })
                        "
                        :hasError="form.errors.hasOwnProperty('phone')"
                    />

                    <InputError :message="form.errors.phone" />
                </div>
                <!-- End Col -->
            </div>
            <!-- End Grid -->
        </div>
        <!-- End Body -->

        <!-- Footer -->
        <div class="flex items-center justify-end border-t border-stone-200 px-5 py-3 dark:border-neutral-700">
            <Button type="submit" :value="__('global.crud.save')" :disabled="form.processing" />
        </div>
        <!-- End Footer -->
    </form>
    <!-- End Card -->
</template>
