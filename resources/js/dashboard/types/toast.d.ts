export interface Toast {
    id?: string;
    message: string;
    type: 'success' | 'danger' | 'info' | 'warning';
}
