import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { RouteName } from 'ziggy-js';

interface Options {
    onSuccess?: VoidFunction;
    onError?: VoidFunction;
    onFinish?: VoidFunction;
}

export default function useDeletion<T>({
    routeName,
    routeDeleteAll,
    options,
}: {
    routeName: RouteName;
    routeDeleteAll?: RouteName;
    options?: Options;
}) {
    const itemToDelete = ref<T | null>(null);

    const openDelete = ref(false);

    const openDeleteItems = ref(false);

    const openDeleteModal = (item: T) => {
        openDelete.value = true;
        itemToDelete.value = item;
    };

    const closeDeleteModal = () => {
        openDelete.value = false;
        itemToDelete.value = null;
    };

    const openDeleteItemsModal = () => {
        openDeleteItems.value = true;
    };

    const closeDeleteItemsModal = () => {
        openDeleteItems.value = false;
    };

    const closeDeleteModals = () => {
        closeDeleteModal();
        closeDeleteItemsModal();
    };

    const confirmDelete = () => {
        if (!itemToDelete.value) return;

        router.delete(route(routeName, itemToDelete.value.id), {
            preserveScroll: true,
            preserveState: false,
            onSuccess: () => options?.onSuccess?.(),
            onError: () => options?.onError?.(),
            onFinish: () => {
                options?.onFinish?.();
                closeDeleteModals();
            },
        });
    };

    const confirmDeleteAll = (ids: (string | number)[]) => {
        if (routeDeleteAll === null) return;
        if (ids.length === 0) return;

        router.delete(route(routeDeleteAll!), {
            preserveScroll: true,
            preserveState: false,
            data: { ids: ids },
            onSuccess: () => options?.onSuccess?.(),
            onError: () => options?.onError?.(),
            onFinish: () => {
                options?.onFinish?.();
                closeDeleteModals();
            },
        });
    };

    return {
        itemToDelete,
        openDelete,
        openDeleteItems,
        confirmDelete,
        confirmDeleteAll,
        openDeleteModal,
        closeDeleteModal,
        openDeleteItemsModal,
        closeDeleteItemsModal,
        closeDeleteModals,
    };
}
