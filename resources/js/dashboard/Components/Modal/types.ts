import { ModalConfig } from '@/dashboard/types/global';

export interface ModalInstance {
    open: () => void;
    close: () => void;
    afterLeave: () => void;
    reload: (...args: any[]) => void;
    setOpen: (open: boolean) => void;
    emit: (event: string, ...args: unknown[]) => void;
    getChildModal: () => any;
    getParentModal: () => any;
    config: ModalConfig | undefined;
    id: string | number | undefined;
    index: number | undefined;
    isOpen: boolean | undefined;
    onTopOfStack: boolean | undefined;
    shouldRender: boolean | undefined;
}

export interface ModalSlot {
    id: string | number;
    index: number;
    isOpen: boolean;
    shouldRender: boolean;
    onTopOfStack: boolean;
    config: ModalConfig;
    close: () => void;
    afterLeave: () => void;
    reload: (...args: any[]) => void;
    setOpen: (open: boolean) => void;
    getChildModal: () => any;
    getParentModal: () => any;
    emit: (...args: any[]) => void;
    [key: string]: any;
}
