import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { RouteName } from '../../../../vendor/tightenco/ziggy/src/js';

interface Options {
    onSuccess?: () => void;
    onError?: () => void;
    onFinish?: () => void;
}

export default function useDeletion<T>(routeName: RouteName, options?: Options) {
    const itemToDelete = ref<T | null>(null);

    const openDelete = ref(false);

    const openDeleteModal = (item: T) => {
        openDelete.value = true;
        itemToDelete.value = item;
    };

    const closeDeleteModal = () => {
        openDelete.value = false;
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
        openDelete,
        confirmDelete,
        openDeleteModal,
        closeDeleteModal,
        resetToDeletionState,
    };
}
