import { router } from '@inertiajs/vue3';
import { debounce, identity, isEqual, pickBy } from 'lodash';
import { computed, ComputedRef, Ref, ref, watch } from 'vue';

export interface Filters {
    search: string;
    per_page: 15 | 25 | 50 | 75 | 100;
    [key: string]: any;
}

export interface Filter {
    filters: Ref<Filters>;
    isModified: ComputedRef<boolean>;
    applyFilters: (data: Record<string, any>) => void;
    updatePerPage: (perPage: Filters['per_page']) => void;
    resetFilters: () => void;
}

export function useFilters(
    endPoint: string,
    propsFilters: Filters,
    defaultFilters: Filters = {
        search: '',
        per_page: 15,
    },
): Filter {
    const filters = ref<Filters>(propsFilters);

    const debouncedApplyFilters = debounce(() => {
        router.get(endPoint, pickBy(filters.value, identity), {
            preserveScroll: true,
            replace: true,
            preserveState: true,
        });
    }, 400);

    watch(filters, debouncedApplyFilters, { deep: true });

    const isModified = computed(() => !isEqual(filters.value, defaultFilters));

    const applyFilters = (data: Record<string, any>) => (filters.value = { ...filters.value, ...data });

    const updatePerPage = (perPage: Filters['per_page']) => applyFilters({ per_page: perPage });

    const resetFilters = () => (filters.value = defaultFilters);

    return {
        filters,
        isModified,
        applyFilters,
        updatePerPage,
        resetFilters,
    };
}
