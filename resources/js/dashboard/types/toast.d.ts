export type ToastType = 'success' | 'danger' | 'info' | 'warning';

export interface Toast {
    id: string;
    message: string;
    type: ToastType;
}

export type ToastReactive = {
    items: Toast[];
    add(message: string, type: ToastType): void;
    success(message: string): void;
    danger(message: string): void;
    info(message: string): void;
    warning(message: string): void;
    remove(id: string): void;
    clear(): void;
};
