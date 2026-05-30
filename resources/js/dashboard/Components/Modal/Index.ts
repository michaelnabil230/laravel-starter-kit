import { cva, type VariantProps } from 'class-variance-authority';

export const contentVariants = cva('data-[state=open]:animate-in data-[state=closed]:animate-out fixed z-100', {
    variants: {
        type: {
            modal: 'data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 top-1/2 left-1/2 w-full -translate-1/2 duration-200',
            slideover:
                'size-full transition ease-in-out data-[state=closed]:duration-200 data-[state=open]:duration-500',
        },
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
        position: {
            top: 'data-[state=closed]:slide-out-to-top data-[state=open]:slide-in-from-top inset-x-0 top-0',
            bottom: 'data-[state=closed]:slide-out-to-bottom data-[state=open]:slide-in-from-bottom inset-x-0 bottom-0',
            left: 'data-[state=closed]:slide-out-to-left data-[state=open]:slide-in-from-left rtl:data-[state=closed]:slide-out-to-right rtl:data-[state=open]:slide-in-from-right inset-y-0 inset-s-0',
            right: 'data-[state=closed]:slide-out-to-right data-[state=open]:slide-in-from-right rtl:data-[state=closed]:slide-out-to-left rtl:data-[state=open]:slide-in-from-left inset-y-0 inset-e-0',
        },
    },
    defaultVariants: {
        type: 'modal',
        size: '2xl',
        position: null,
    },
});

export const innerVariants = cva('flex flex-col', {
    variants: {
        type: {
            modal: 'pointer-events-auto max-h-[calc(100vh-2rem)] w-full overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-2xs dark:border-neutral-800 dark:bg-neutral-800',
            slideover: 'size-full flex-1 border-s border-gray-200 bg-white dark:border-neutral-700 dark:bg-neutral-800',
        },
    },
    defaultVariants: {
        type: 'modal',
    },
});

export type ContentVariants = VariantProps<typeof contentVariants>;
export type InnerVariants = VariantProps<typeof innerVariants>;

export { default as Modal } from './Modal.vue';
export { default as ModalClose } from './ModalClose.vue';
export { default as ModalDescription } from './ModalDescription.vue';
export { default as ModalFooter } from './ModalFooter.vue';
export { default as ModalHeader } from './ModalHeader.vue';
export { default as ModalLink } from './ModalLink.vue';
export { default as ModalTitle } from './ModalTitle.vue';
