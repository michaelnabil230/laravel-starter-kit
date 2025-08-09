import { App } from '@/dashboard/types/app';
import { CountryCode } from 'libphonenumber-js';
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

export interface SelectProps extends BaseInputProps {
    attribute: string;
    endPoint?: string;
    options?: Option[];
    selected?: Option | Option[] | null;
    classViewPort?: HTMLAttributes['class'];
    hasSearch?: boolean;
    allowEmpty?: boolean;
}

export interface Option {
    value: string | null;
    label: string;
    [name: string]: any;
}

export interface PhoneInputProps extends BaseInputProps {
    defaultPhoneValue?: string;
    defaultPhoneCountryValue?: string;
    countryCode?: CountryCode;
}

export interface TextareaProps extends InputWithDefaultValueProps {
    autoSize?: boolean;
}

// eslint-disable-next-line @typescript-eslint/no-empty-object-type
export interface TextareaEditorProps extends TextareaProps {}

export interface SwitchProps extends BaseInputProps {
    defaultValue?: boolean;
}

export interface CheckboxProps extends BaseInputProps {
    defaultValue?: boolean | null;
}

export interface RadioProps extends BaseInputProps {
    defaultValue?: string | null;
}
