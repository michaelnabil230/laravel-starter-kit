import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { RouteName } from 'ziggy-js';
import { App } from '../types/app';
import useLocalization from './useLocalization';
import useToasts from './useToasts';

interface Options {
    onSuccess?: VoidFunction;
    onError?: VoidFunction;
    onFinish?: VoidFunction;
}

export default function useImport(routeName: RouteName, options?: Options) {
    const openImport = ref(false);

    const openImportModal = () => {
        openImport.value = true;
    };

    const closeImportModal = () => {
        openImport.value = false;
    };

    const confirmImport = (media: App.Models.Media | null) => {
        const { __ } = useLocalization();
        const toasts = useToasts();

        if (!media) {
            toasts.danger(__('import.no_file_selected'));
            return;
        }

        router.post(
            routeName,
            { media: { ...media } },
            {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    options?.onSuccess?.();
                    toasts.success(__('import.success'));
                },
                onError: () => {
                    options?.onError?.();
                    toasts.danger(__('errors.error_happened'));
                },
                onFinish: () => {
                    options?.onFinish?.();
                    closeImportModal();
                },
            },
        );
    };

    return {
        openImport,
        openImportModal,
        closeImportModal,
        confirmImport,
    };
}
