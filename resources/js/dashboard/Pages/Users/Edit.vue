<script setup lang="ts">
import Button from '@/dashboard/Components/Button/Button.vue';
import ButtonLink from '@/dashboard/Components/Button/ButtonLink.vue';
import Checkbox from '@/dashboard/Components/Inputs/Checkbox.vue';
import DateInput from '@/dashboard/Components/Inputs/DateInput.vue';
import InputError from '@/dashboard/Components/Inputs/InputError.vue';
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import PhoneInput from '@/dashboard/Components/Inputs/PhoneInput.vue';
import Select from '@/dashboard/Components/Inputs/Select.vue';
import Switch from '@/dashboard/Components/Inputs/Switch.vue';
import TextInput from '@/dashboard/Components/Inputs/TextInput.vue';
import { Option } from '@/dashboard/Components/Inputs/types';
import useLocalization from '@/dashboard/composables/useLocalization';
import useToasts from '@/dashboard/composables/useToasts';
import AppLayout from '@/dashboard/Layouts/AppLayout.vue';
import { App } from '@/dashboard/types/app';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const { __ } = useLocalization();
const toasts = useToasts();

const breadcrumbs: Breadcrumbs = [
    { text: __('dashboard'), href: route('dashboard.welcome') },
    { text: __('resources.user.plural'), href: route('dashboard.users.index') },
    { text: __('global.crud.edit') },
];

const props = defineProps<{
    user: App.Models.User;
    medical_questions: App.Models.MedicalQuestion[];
}>();

const form = useForm({
    name: props.user.name,
    phone: props.user.phone,
    birth_date: props.user.birth_date,
    gender: props.user.gender,
    national_id: props.user.national_id,
    source: props.user.source,
    medical_question_answers: props.user.medical_question_answers ?? [],
    is_old: props.user.is_old,
});

const submit = () => {
    form.put(route('dashboard.users.update', props.user.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toasts.success(__('global.actions.updated', { resource: __('resources.user.singular') }));
            form.reset();
        },
        onError: () => toasts.danger(__('errors.error_happened')),
    });
};

const genders = computed(() => {
    return Object.values(App.Enums.Gender).map((gender) => {
        return {
            label: __('global.enums.gender.' + gender),
            value: gender,
        } as Option;
    });
});

const sources = computed(() => {
    return Object.values(App.Enums.Source).map((source) => {
        return {
            label: __('global.enums.source.' + source),
            value: source,
        } as Option;
    });
});
</script>

<template>
    <AppLayout
        :title="
            __('global.resource.edit', {
                resource: __('resources.user.singular'),
            })
        "
        :breadcrumbs="breadcrumbs"
    >
        <form
            @submit.prevent="submit()"
            class="flex flex-col rounded-xl border border-stone-200 bg-white shadow-2xs dark:border-neutral-700 dark:bg-neutral-800"
        >
            <div
                class="flex items-center justify-between gap-x-5 border-b border-stone-200 px-5 py-3 dark:border-neutral-700"
            >
                <h2 class="inline-block font-semibold text-stone-800 dark:text-neutral-200">
                    {{
                        __('global.resource.edit', {
                            resource: __('resources.user.singular'),
                        })
                    }}
                </h2>
            </div>

            <div class="space-y-4 p-5">
                <div>
                    <InputLabel :value="__('global.attributes.name')" for="name" />

                    <TextInput
                        v-model="form.name"
                        type="text"
                        id="name"
                        :placeholder="
                            __('global.placeholder', {
                                attribute: __('global.attributes.name'),
                            })
                        "
                        :hasError="form.errors.hasOwnProperty('name')"
                    />

                    <InputError :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel :value="__('global.attributes.phone')" for="phone" />

                    <PhoneInput
                        id="phone"
                        v-model="form.phone"
                        :placeholder="
                            __('global.placeholder', {
                                attribute: __('global.attributes.phone'),
                            })
                        "
                        :hasError="form.errors.hasOwnProperty('phone')"
                    />

                    <InputError :message="form.errors.phone" />
                </div>

                <div class="grid gap-y-1.5 sm:grid-cols-12 sm:gap-x-5 sm:gap-y-0">
                    <div class="col-span-6">
                        <InputLabel :value="__('global.gender')" for="gender" />

                        <Select
                            :placeholder="__('global.choose', { attribute: __('global.gender') })"
                            v-model="form.gender"
                            :options="genders"
                            :hasError="form.errors.hasOwnProperty('gender')"
                            id="gender"
                        />

                        <InputError :message="form.errors.gender" />
                    </div>

                    <div class="col-span-6">
                        <InputLabel :value="__('resources.user.attributes.birth_date')" for="birth_date" />

                        <DateInput
                            v-model="form.birth_date"
                            id="birth_date"
                            :placeholder="
                                __('global.placeholder', {
                                    attribute: __('resources.user.attributes.birth_date'),
                                })
                            "
                            :hasError="form.errors.hasOwnProperty('birth_date')"
                        />

                        <InputError :message="form.errors.birth_date" />
                    </div>
                </div>

                <div>
                    <InputLabel :value="__('global.source')" for="source" />

                    <Select
                        :placeholder="__('global.choose', { attribute: __('global.source') })"
                        v-model="form.source"
                        :options="sources"
                        :hasError="form.errors.hasOwnProperty('source')"
                        id="source"
                    />

                    <InputError :message="form.errors.source" />
                </div>

                <div>
                    <InputLabel :value="__('resources.user.attributes.national_id')" for="national_id" />

                    <TextInput
                        v-model="form.national_id"
                        type="number"
                        id="national_id"
                        :placeholder="
                            __('global.placeholder', {
                                attribute: __('resources.user.attributes.national_id'),
                            })
                        "
                        :hasError="form.errors.hasOwnProperty('national_id')"
                    />

                    <InputError :message="form.errors.national_id" />
                </div>

                <div v-if="user.is_old">
                    <InputLabel :value="__('resources.user.attributes.is_old')" for="is_old" />

                    <Switch id="is_old" v-model="form.is_old" />
                </div>

                <div>
                    <InputLabel :value="__('resources.medical_question.plural')" for="medical_question_answers" />

                    <div v-if="medical_questions.length > 0" class="space-y-4">
                        <div
                            v-for="question in medical_questions"
                            :key="question.id"
                            class="rounded-xl border border-stone-200 p-4 dark:border-neutral-700"
                        >
                            <p class="mb-3 text-sm font-semibold text-stone-800 dark:text-neutral-200">
                                {{ question.question.current }}
                            </p>

                            <div class="space-y-2">
                                <label
                                    v-for="answer in question.medical_answers ?? []"
                                    :key="`medical-answer-${answer.id}`"
                                    class="flex cursor-pointer items-center gap-2"
                                >
                                    <Checkbox
                                        :id="`medical-answer-${answer.id}`"
                                        v-model="form.medical_question_answers"
                                        :value="answer.id"
                                    />
                                    <span class="text-sm text-stone-700 dark:text-neutral-300">
                                        {{ answer.answer.current }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="flex items-center justify-end gap-x-2.5 border-t border-stone-200 px-5 py-3 dark:border-neutral-700"
            >
                <ButtonLink
                    color="secondary"
                    variant="outlined"
                    :href="route('dashboard.users.index')"
                    :value="__('global.closure.cancel')"
                />

                <Button type="submit" :value="__('global.crud.edit')" :disabled="form.processing" />
            </div>
        </form>
    </AppLayout>
</template>
