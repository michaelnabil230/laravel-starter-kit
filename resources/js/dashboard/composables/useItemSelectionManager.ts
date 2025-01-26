import { ref, watch } from 'vue';

type Identifiable = {
    id: number | string;
};

export default function useItemSelectionManager(items: Identifiable[]) {
    const isAllSelected = ref(false);

    const isIndeterminate = ref(false);

    const selectedList = ref<(string | number)[]>([]);

    const updateCheckboxState = () => {
        const checkedCount = selectedList.value.length;
        const totalItems = items.length;

        isAllSelected.value = checkedCount === totalItems;
        isIndeterminate.value = checkedCount > 0 && checkedCount < totalItems;
    };

    const toggleItemSelect = (item: string | number) => {
        const index = selectedList.value.findIndex((i) => i === item);
        if (index !== -1) {
            selectedList.value.splice(index, 1);
        } else {
            selectedList.value.push(item);
        }
    };

    const toggleSelectAll = () => {
        isAllSelected.value = !isAllSelected.value;
        if (isAllSelected.value) {
            selectedList.value = [...items.map((item) => item.id)];
        } else {
            selectedList.value = [];
        }
    };

    watch(selectedList, updateCheckboxState, { deep: true });

    const resetItemSelection = () => {
        selectedList.value = [];
    };

    return {
        isAllSelected,
        isIndeterminate,
        selectedList,
        toggleItemSelect,
        toggleSelectAll,
        resetItemSelection,
    };
}
