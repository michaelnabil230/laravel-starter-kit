import { HSOverlay } from 'preline/preline';

export default function useImport(resource: string) {
    const openImportModal = () => {
        HSOverlay.open(document.getElementById('import-' + resource) as HTMLElement);
    };

    const closeImportModal = () => {
        HSOverlay.close(document.getElementById('import-' + resource) as HTMLElement);
    };

    const confirmImport = () => closeImportModal();

    return {
        openImportModal,
        closeImportModal,
        confirmImport,
    };
}
