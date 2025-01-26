import { router } from '@inertiajs/vue3';
import { ref, Ref } from 'vue';
import { RouteName } from 'ziggy-js';
import { Filters } from './useFilter';
import useLocalization from './useLocalization';
import useToasts from './useToasts';

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
        const toasts = useToasts();

        router.post(
            routeName,
            { ...filters.value, export_type: type },
            {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    options?.onSuccess?.();
                    toasts.success(__('export.success'));
                },
                onError: () => {
                    options?.onError?.();
                    toasts.danger(__('errors.error_happened'));
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
