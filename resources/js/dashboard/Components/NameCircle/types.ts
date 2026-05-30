import { VariantProps } from 'class-variance-authority';
import { nameCircleVariants } from './Index';

type NameCircleVariants = VariantProps<typeof nameCircleVariants>;

export interface NameCircleProps {
    name: string;
    size?: NameCircleVariants['size'];
}
