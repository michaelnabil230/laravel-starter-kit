import { cva } from 'class-variance-authority';

export const nameCircleVariants = cva(
    'flex items-center justify-center rounded-full bg-blue-600 font-medium text-white select-none dark:bg-blue-700',
    {
        variants: {
            size: {
                small: 'size-8 text-xs',
                medium: 'size-10 text-sm',
                large: 'size-12 text-base',
            },
        },
        defaultVariants: {
            size: 'medium',
        },
    },
);
