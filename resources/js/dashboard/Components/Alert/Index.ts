import { cva, type VariantProps } from 'class-variance-authority';

export const alertVariants = cva('mt-2 rounded-lg p-4 text-sm', {
    variants: {
        variant: {
            solid: 'text-white',
            soft: 'border',
        },
        color: {
            primary: 'bg-gray-800 dark:bg-white',
            secondary: 'bg-gray-500',
            info: 'bg-blue-600 dark:bg-blue-500',
            success: 'bg-teal-500',
            danger: 'bg-red-500',
            warning: 'bg-yellow-500',
        },
    },
    compoundVariants: [
        {
            variant: 'soft',
            color: 'primary',
            class: 'border-gray-200 bg-gray-100 text-gray-800 dark:border-white/20 dark:bg-white/10 dark:text-white',
        },
        {
            variant: 'soft',
            color: 'secondary',
            class: 'border-gray-200 bg-gray-50 text-gray-600 dark:border-white/10 dark:bg-white/10 dark:text-neutral-400',
        },
        {
            variant: 'soft',
            color: 'info',
            class: 'border-blue-200 bg-blue-100 text-blue-800 dark:border-blue-900 dark:bg-blue-800/10 dark:text-blue-500',
        },
        {
            variant: 'soft',
            color: 'success',
            class: 'border-teal-200 bg-teal-100 text-teal-800 dark:border-teal-900 dark:bg-teal-800/10 dark:text-teal-500',
        },
        {
            variant: 'soft',
            color: 'danger',
            class: 'border-red-200 bg-red-100 text-red-800 dark:border-red-900 dark:bg-red-800/10 dark:text-red-500',
        },
        {
            variant: 'soft',
            color: 'warning',
            class: 'border-yellow-200 bg-yellow-100 text-yellow-800 dark:border-yellow-900 dark:bg-yellow-800/10 dark:text-yellow-500',
        },
    ],
    defaultVariants: {
        color: 'primary',
        variant: 'solid',
    },
});

export type AlertVariants = VariantProps<typeof alertVariants>;
