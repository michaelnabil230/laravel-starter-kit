import { router } from '@inertiajs/vue3';
import { HSOverlay } from 'preline/preline';
import { ref } from 'vue';
import { RouteName } from '../../../../vendor/tightenco/ziggy/src/js';

interface Options {
    onSuccess?: () => void;
    onError?: () => void;
    onFinish?: () => void;
}

export default function useDeletion<T>(resource: string, routeName: RouteName, options?: Options) {
    const itemToDelete = ref<T | null>(null);

    const openDeleteModal = (item: T) => {
        HSOverlay.open(document.getElementById('delete-' + resource + '-record') as HTMLElement);
        itemToDelete.value = item;
    };

    const closeDeleteModal = () => {
        HSOverlay.close(document.getElementById('delete-' + resource + '-record') as HTMLElement);
        resetToDeletionState();
    };

    const confirmDelete = () => {
        if (!itemToDelete.value) return;

        router.delete(route(routeName, itemToDelete.value.id), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => options?.onSuccess?.(),
            onError: () => options?.onError?.(),
            onFinish: () => {
                options?.onFinish?.();
                closeDeleteModal();
            },
        });
    };

    const resetToDeletionState = () => {
        itemToDelete.value = null;
    };

    return {
        itemToDelete,
        confirmDelete,
        openDeleteModal,
        closeDeleteModal,
        resetToDeletionState,
    };
}
