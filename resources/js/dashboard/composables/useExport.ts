import { visitModal } from '@/dashboard/composables/modal/modalStack';
import { router } from '@inertiajs/vue3';
import { Ref } from 'vue';
import { RouteName } from 'ziggy-js';
import { Filters } from './useFilter';
import useLocalization from './useLocalization';
import useToasts from './useToasts';

interface Options {
    onSuccess?: VoidFunction;
    onError?: VoidFunction;
    onFinish?: VoidFunction;
}

export function useExport({
    resource,
    routeName,
    filters,
    options,
}: {
    resource: string;
    routeName: RouteName;
    filters: Ref<Partial<Filters>>;
    options?: Options;
}) {
    const openExportModal = () => {
        visitModal(`#export-${resource}`);
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
                },
            },
        );
    };

    return {
        openExportModal,
        confirmExport,
    };
}
