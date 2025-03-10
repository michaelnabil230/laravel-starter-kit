import { ref } from 'vue';

export default function useImport() {
    const openImport = ref(false);

    const openImportModal = () => {
        openImport.value = true;
    };

    const closeImportModal = () => {
        openImport.value = false;
    };

    const confirmImport = () => {
        closeImportModal();
    };

    return {
        openImport,
        openImportModal,
        closeImportModal,
        confirmImport,
    };
}
