import { cva, type VariantProps } from 'class-variance-authority';

export const buttonVariants = cva(
    'inline-flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[disabled=true]:opacity-50 data-[disabled=true]:pointer-events-none [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0',
    {
        variants: {
            variant: {
                contained: 'text-white',
                outlined: 'border',
                text: 'bg-transparent',
            },
            color: {
                primary: '',
                secondary: '',
                danger: '',
            },
            size: {
                small: 'py-2 px-3',
                medium: 'py-3 px-4',
                large: 'p-4 sm:p-5',
            },
        },
        compoundVariants: [
            // Contained (Solid) variants
            {
                variant: 'contained',
                color: 'primary',
                class: 'bg-blue-600 text-white hover:bg-blue-700',
            },
            {
                variant: 'contained',
                color: 'secondary',
                class: 'bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700',
            },
            {
                variant: 'contained',
                color: 'danger',
                class: 'bg-red-600 text-white hover:bg-red-700',
            },
            // Outlined variants
            {
                variant: 'outlined',
                color: 'primary',
                class: 'border-blue-600 text-blue-600 hover:border-blue-500 hover:text-blue-500 dark:border-blue-500 dark:text-blue-500 dark:hover:text-blue-400 dark:hover:border-blue-400',
            },
            {
                variant: 'outlined',
                color: 'secondary',
                class: 'border-neutral-200 text-gray-800 hover:border-neutral-300 hover:text-gray-900  dark:border-neutral-700 dark:text-neutral-300 dark:hover:border-neutral-600 dark:hover:text-neutral-200',
            },
            {
                variant: 'outlined',
                color: 'danger',
                class: 'border-red-500 text-red-500 hover:border-red-600 hover:text-red-600 dark:border-red-500 dark:text-red-500 dark:hover:border-red-400 dark:hover:text-red-400',
            },
            // Text variants
            {
                variant: 'text',
                color: 'danger',
                class: 'border border-gray-200 bg-white text-red-500 hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-700',
            },
        ],
        defaultVariants: {
            variant: 'contained',
            size: 'small',
            color: 'primary',
        },
    },
);

export type ButtonVariants = VariantProps<typeof buttonVariants>;
