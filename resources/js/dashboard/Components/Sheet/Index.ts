import { cva, type VariantProps } from 'class-variance-authority';

export const sheetVariants = cva(
    'data-[state=open]:animate-in data-[state=closed]:animate-out fixed z-100 size-full transition ease-in-out data-[state=closed]:duration-200 data-[state=open]:duration-500',
    {
        variants: {
            size: {
                sm: 'max-w-sm',
                md: 'max-w-md',
                lg: 'max-w-lg',
                xl: 'max-w-xl',
                '2xl': 'max-w-2xl',
                '3xl': 'max-w-3xl',
                '4xl': 'max-w-4xl',
                '5xl': 'max-w-5xl',
                '6xl': 'max-w-6xl',
                '7xl': 'max-w-7xl',
            },
            side: {
                top: 'data-[state=closed]:slide-out-to-top data-[state=open]:slide-in-from-top inset-x-0 top-0',
                bottom: 'data-[state=closed]:slide-out-to-bottom data-[state=open]:slide-in-from-bottom inset-x-0 bottom-0',
                left: 'data-[state=closed]:slide-out-to-left data-[state=open]:slide-in-from-left rtl:data-[state=closed]:slide-out-to-right rtl:data-[state=open]:slide-in-from-right inset-y-0 start-0',
                right: 'data-[state=closed]:slide-out-to-right data-[state=open]:slide-in-from-right rtl:data-[state=closed]:slide-out-to-left rtl:data-[state=open]:slide-in-from-left inset-y-0 end-0',
            },
        },
        defaultVariants: {
            size: 'md',
            side: 'right',
        },
    },
);

export type SheetVariants = VariantProps<typeof sheetVariants>;
