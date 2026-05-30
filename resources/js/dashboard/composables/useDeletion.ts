import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { RouteName } from 'ziggy-js';
import { visitModal } from './modal/modalStack';

interface Options {
    onSuccess?: VoidFunction;
    onError?: VoidFunction;
    onFinish?: VoidFunction;
}

type RouteDelete = (id?: string | number) => RouteName;

type RouteDeleteAll = () => RouteName;

export function useDeletion<T>({
    resource,
    routeName,
    routeDeleteAll,
    options,
}: {
    resource: string;
    routeName: RouteDelete;
    routeDeleteAll?: RouteDeleteAll | null;
    options?: Options;
}) {
    const itemToDelete = ref<T | null>(null);

    const openDeleteModal = (item: T) => {
        itemToDelete.value = item;
        visitModal('#delete-' + resource);
    };

    const openDeleteItemsModal = () => {
        visitModal('#delete-all-' + resource);
    };

    const confirmDelete = () => {
        if (!itemToDelete.value) return;

        router.delete(routeName(itemToDelete.value.id), {
            preserveScroll: true,
            preserveState: false,
            onSuccess: () => options?.onSuccess?.(),
            onError: () => options?.onError?.(),
            onFinish: () => {
                options?.onFinish?.();
            },
        });
    };

    const confirmDeleteAll = (ids: (string | number)[]) => {
        if (routeDeleteAll === null) return;
        if (ids.length === 0) return;

        router.delete(routeDeleteAll!(), {
            preserveScroll: true,
            preserveState: false,
            data: { ids: ids },
            onSuccess: () => options?.onSuccess?.(),
            onError: () => options?.onError?.(),
            onFinish: () => {
                options?.onFinish?.();
            },
        });
    };

    return {
        itemToDelete,
        confirmDelete,
        confirmDeleteAll,
        openDeleteModal,
        openDeleteItemsModal,
    };
}
