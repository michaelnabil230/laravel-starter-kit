import { router } from '@inertiajs/vue3';
import { ref, Ref } from 'vue';
import { RouteName } from '../../../../vendor/tightenco/ziggy/src/js';
import { Filters } from './useFilter';
import useLocalization from './useLocalization';

interface Options {
    onSuccess?: () => void;
    onError?: () => void;
    onFinish?: () => void;
}

export default function useExport(routeName: RouteName, filters: Ref<Partial<Filters>>, options?: Options) {
    const openExport = ref(false);

    const openExportModal = () => {
        openExport.value = true;
    };

    const closeExportModal = () => {
        openExport.value = false;
    };

    const confirmExport = (type: string) => {
        const { __ } = useLocalization();

        router.post(
            routeName,
            { ...filters.value, export_type: type },
            {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    options?.onSuccess?.();
                    dashboardApp.success(__('export.success'));
                },
                onError: () => {
                    options?.onError?.();
                    dashboardApp.danger(__('errors.error_happened'));
                },
                onFinish: () => {
                    options?.onFinish?.();
                    closeExportModal();
                },
            },
        );
    };

    return {
        openExport,
        openExportModal,
        closeExportModal,
        confirmExport,
    };
}
