import { Toast, ToastReactive, ToastType } from '@/dashboard/types/toast';
import { reactive } from 'vue';

export default reactive<ToastReactive>({
    items: [] as Toast[],
    add(message: string, type: ToastType = 'info'): void {
        this.items.unshift({
            id: Date.now().toString(),
            message,
            type,
        });
    },
    success(message: string): void {
        this.add(message, 'success');
    },
    danger(message: string): void {
        this.add(message, 'danger');
    },
    info(message: string): void {
        this.add(message, 'info');
    },
    warning(message: string): void {
        this.add(message, 'warning');
    },
    remove(id: string): void {
        this.items = this.items.filter((toast) => toast.id !== id);
    },
    clear(): void {
        this.items = [];
    },
});
