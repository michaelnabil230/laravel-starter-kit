import { HSOverlay } from 'preline/preline';

export default function useExport(resource: string) {
    const openExportModal = () => {
        HSOverlay.open(document.getElementById('export-' + resource) as HTMLElement);
    };

    const closeExportModal = () => {
        HSOverlay.close(document.getElementById('export-' + resource) as HTMLElement);
    };

    const confirmExport = () => closeExportModal();

    return {
        openExportModal,
        closeExportModal,
        confirmExport,
    };
}
