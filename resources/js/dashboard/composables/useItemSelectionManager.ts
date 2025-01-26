import { router } from '@inertiajs/vue3';
import { HSOverlay } from 'preline/preline';
import { ref, watch } from 'vue';

interface Identifiable {
    id: number | string;
}

interface Options {
    onSuccess?: () => void;
    onError?: () => void;
    onFinish?: () => void;
}

export default function useItemSelectionManager(
    resource: string,
    items: Identifiable[],
    endPoint: string,
    options?: Options,
) {
    // States
    const isAllSelected = ref(false);
    const isIndeterminate = ref(false);
    const selectedList = ref<Identifiable[]>([]);

    // Function to update checkbox state
    const updateCheckboxState = () => {
        const checkedCount = selectedList.value.length;
        const totalItems = items.length;

        isAllSelected.value = checkedCount === totalItems;
        isIndeterminate.value = checkedCount > 0 && checkedCount < totalItems;
    };

    // Function to toggle selection of a single item
    const toggleItemSelect = (item: Identifiable) => {
        const index = selectedList.value.findIndex((i) => i.id === item.id);
        if (index !== -1) {
            selectedList.value.splice(index, 1);
        } else {
            selectedList.value.push(item);
        }
    };

    // Function to toggle select/deselect all items
    const toggleSelectAll = () => {
        isAllSelected.value = !isAllSelected.value;
        if (isAllSelected.value) {
            selectedList.value = [...items];
        } else {
            selectedList.value = [];
        }
    };

    // Watch selected items and update the checkbox state
    watch(selectedList, updateCheckboxState, { deep: true });

    const openDeleteItemsModal = () => {
        HSOverlay.open(document.getElementById('delete-all-' + resource + '-records') as HTMLElement);
    };

    const closeDeleteItemsModal = () => {
        HSOverlay.close(document.getElementById('delete-all-' + resource + '-records') as HTMLElement);
    };

    // Function to confirm deletion
    const confirmDeleteAll = () => {
        closeDeleteItemsModal();

        router.delete(endPoint, {
            preserveScroll: true,
            preserveState: true,
            data: { ids: selectedList.value.map((item) => item.id) },
            onSuccess: () => options?.onSuccess?.(),
            onError: () => options?.onError?.(),
            onFinish: () => {
                options?.onFinish?.();
                resetDeletionState();
            },
        });
    };

    // Reset the deletion state
    const resetDeletionState = () => {
        selectedList.value = [];
    };

    return {
        isAllSelected,
        isIndeterminate,
        selectedList,
        toggleItemSelect,
        toggleSelectAll,
        openDeleteItemsModal,
        closeDeleteItemsModal,
        confirmDeleteAll,
        resetDeletionState,
    };
}
