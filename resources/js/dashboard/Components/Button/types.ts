import { VariantProps } from 'class-variance-authority';
import { HTMLAttributes } from 'vue';
import { buttonVariants } from './Index';

type ButtonVariants = VariantProps<typeof buttonVariants>;

export interface BaseButtonProps {
    variant?: ButtonVariants['variant'];
    color?: ButtonVariants['color'];
    size?: ButtonVariants['size'];
    class?: HTMLAttributes['class'];
    disabled?: boolean;
    value?: string;
}

export interface ButtonProps extends BaseButtonProps {
    type?: 'button' | 'submit' | 'reset';
}
