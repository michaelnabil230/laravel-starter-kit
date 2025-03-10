import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface Identifiable {
    id: number | string;
}

interface Options {
    onSuccess?: () => void;
    onError?: () => void;
    onFinish?: () => void;
}

export default function useItemSelectionManager(items: Identifiable[], url: string, options?: Options) {
    const isAllSelected = ref(false);
    const isIndeterminate = ref(false);
    const selectedList = ref<Identifiable[]>([]);
    const openDeleteItems = ref(false);

    const updateCheckboxState = () => {
        const checkedCount = selectedList.value.length;
        const totalItems = items.length;

        isAllSelected.value = checkedCount === totalItems;
        isIndeterminate.value = checkedCount > 0 && checkedCount < totalItems;
    };

    const toggleItemSelect = (item: Identifiable) => {
        const index = selectedList.value.findIndex((i) => i.id === item.id);
        if (index !== -1) {
            selectedList.value.splice(index, 1);
        } else {
            selectedList.value.push(item);
        }
    };

    const toggleSelectAll = () => {
        isAllSelected.value = !isAllSelected.value;
        if (isAllSelected.value) {
            selectedList.value = [...items];
        } else {
            selectedList.value = [];
        }
    };

    watch(selectedList, updateCheckboxState, { deep: true });

    const openDeleteItemsModal = () => {
        openDeleteItems.value = true;
    };

    const closeDeleteItemsModal = () => {
        openDeleteItems.value = false;
    };

    const confirmDeleteAll = () => {
        closeDeleteItemsModal();

        router.delete(url, {
            preserveScroll: true,
            preserveState: true,
            data: { ids: selectedList.value.map((item) => item.id) },
            onSuccess: () => options?.onSuccess?.(),
            onError: () => options?.onError?.(),
            onFinish: () => {
                options?.onFinish?.();
                resetItemSelection();
            },
        });
    };

    const resetItemSelection = () => {
        selectedList.value = [];
    };

    return {
        isAllSelected,
        isIndeterminate,
        selectedList,
        openDeleteItems,
        toggleItemSelect,
        toggleSelectAll,
        openDeleteItemsModal,
        closeDeleteItemsModal,
        confirmDeleteAll,
        resetItemSelection,
    };
}
