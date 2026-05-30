<script setup lang="ts">
import DateInput from '@/dashboard/Components/Inputs/DateInput.vue';
import InputError from '@/dashboard/Components/Inputs/InputError.vue';
import InputLabel from '@/dashboard/Components/Inputs/InputLabel.vue';
import PhoneInput from '@/dashboard/Components/Inputs/PhoneInput.vue';
import Select from '@/dashboard/Components/Inputs/Select.vue';
import TextInput from '@/dashboard/Components/Inputs/TextInput.vue';
import { Option } from '@/dashboard/Components/Inputs/types';
import useLocalization from '@/dashboard/composables/useLocalization';
import useToasts from '@/dashboard/composables/useToasts';
import { App } from '@/dashboard/types/app';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    asModal: boolean;
}>();

const emit = defineEmits<{
    created: [user: Option];
}>();

const { __ } = useLocalization();
const toasts = useToasts();

const form = useForm({
    name: null,
    phone: null,
    birth_date: null,
    gender: null,
    national_id: null,
    source: null,
});

const submit = () => {
    form.post(route('dashboard.users.store', { as_modal: props.asModal }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            toasts.success(__('global.actions.created', { resource: __('resources.user.singular') }));
            form.reset();
            if (page.flash.createdUser !== undefined) {
                emit('created', page.flash.createdUser as Option);
            }
        },
        onError: () => toasts.danger(__('errors.error_happened')),
    });
};

defineExpose({
    form,
    submit,
});

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
    </div>
</template>
