import { inject, toValue } from 'vue';
import type { Modal } from './modal/modalStack';

export const modalContextKey = Symbol('modalContext');

export default function useModal(): Modal | null {
    return toValue(inject<Modal | null>(modalContextKey, null));
}
