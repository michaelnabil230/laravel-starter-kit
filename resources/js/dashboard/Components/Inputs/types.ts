import { App } from '@/dashboard/types/app';
import { type HTMLAttributes } from 'vue';

export interface BaseInputProps {
    id: string;
    class?: HTMLAttributes['class'];
    hasError?: boolean;
    disabled?: boolean;
    placeholder?: string;
}

export interface InputWithDefaultValueProps extends BaseInputProps {
    defaultValue?: string | null;
}

export interface NumberInputProps extends BaseInputProps {
    defaultValue?: number | null;
}

export interface FileUploadProps extends BaseInputProps {
    defaultValue?: App.Models.Media | App.Models.Media[] | null;
    multiple?: boolean;
    maxItems?: number;
    maxSize?: number;
    acceptedFileTypes?: string[];
}

export interface SelectProps {
    id: string;
    modelValue?: string | number | (string | number)[] | null;
    options?: Option[];
    endPoint?: string | null;
    placeholder: string;
    initialValue?: Option | null;
    debounceMs?: number;
    disabled?: boolean;
    hasError?: boolean;
    multiple?: boolean;
    searchable?: boolean;
    clearable?: boolean;
    ignoreFilter?: boolean;
    creatable?: boolean;
    modalName?: string;
}

export interface Option {
    value: string | number;
    label: string;
    disabled?: boolean;
}

export interface TextareaProps extends InputWithDefaultValueProps {
    autoSize?: boolean;
}

export interface TextareaEditorProps extends TextareaProps {
    mentionOptions?: Option[];
    mentionEndpoint?: string;
}

export interface SwitchProps extends BaseInputProps {
    defaultValue?: boolean;
}

export interface CheckboxProps extends BaseInputProps {
    defaultValue?: boolean | null | string[];
}

export interface RadioProps extends BaseInputProps {
    defaultValue?: string | null;
}
