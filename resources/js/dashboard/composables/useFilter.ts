import { router } from '@inertiajs/vue3';
import { clone, debounce, forEach, identity, isEmpty, isEqual, pickBy } from 'lodash';
import qs from 'qs';
import { computed, ComputedRef, Ref, ref, watch } from 'vue';
import { RouteName } from 'ziggy-js';

export interface Filter {
    openFilter: Ref<boolean>;
    queryBuilderData: Ref<Partial<Filters>>;
    canBeReset: ComputedRef<boolean>;
    resetQuery: () => void;
    onPageChange: (value: number) => void;
    onPerPageChange: (value: number) => void;
    orderByField: (value: string) => void;
    resetOrderBy: () => void;
    onTrashedChange: (value: string) => void;
    openModal: () => void;
    closeModal: () => void;
}

export interface Filters {
    page: number;
    per_page: number;
    search: string;
    order_by: string;
    order_by_direction: string;
    trashed: string;
    [key: string]: any;
}

export function useFilter(
    routeName: RouteName,
    filters?: Filters,
    defaultFilters: Record<string, any> = {},
    wait: number = 400,
): Filter {
    const openFilter = ref(false);

    const openModal = () => (openFilter.value = true);

    const closeModal = () => (openFilter.value = false);

    const _ignoredKeys = ['search', 'page', 'per_page', 'order_by', 'order_by_direction', 'trashed'];

    const dataForNewQueryString = (query: Filters) => {
        const queryData: Record<string, any> = {};

        if (query.search) queryData.search = query.search;
        if (query.page > 1) queryData.page = query.page;
        if (query.per_page > 1) queryData.per_page = query.per_page;
        if (query.order_by) queryData.order_by = query.order_by;
        if (query.order_by_direction) queryData.order_by_direction = query.order_by_direction;
        if (query.trashed) queryData.trashed = query.trashed;

        forEach(query, (value, key) => {
            if (!_ignoredKeys.includes(key)) {
                queryData[key] = value;
            }
        });

        return queryData;
    };

    const queryBuilderProps = computed(() => {
        return {
            ...filters,
            ...dataForNewQueryString(qs.parse(location.search.substring(1)) as Filters),
        };
    });

    const queryBuilderData = ref(queryBuilderProps.value);

    const debouncedApplyFilters = debounce(() => {
        router.get(routeName, pickBy(dataForNewQueryString(queryBuilderData.value as Filters), identity), {
            preserveScroll: true,
            replace: true,
            preserveState: true,
        });
    }, wait);

    watch(queryBuilderData, debouncedApplyFilters, { deep: true });

    const canBeReset = computed(() => {
        const queryData = clone(queryBuilderData.value);

        if (
            !isEmpty(queryData.search) ||
            !isEmpty(queryData.trashed) ||
            !isEmpty(queryData.order_by) ||
            !isEmpty(queryData.order_by_direction)
        ) {
            return true;
        }

        _ignoredKeys.map((key) => delete queryData[key]);

        return !isEqual(queryData, defaultFilters);
    });

    const resetQuery = () => {
        const queryData = {
            search: '',
            page: 1,
            trashed: '',
            order_by: '',
            order_by_direction: '',
        } as Filters;

        const ignoredKeys = ['per_page', ...Object.keys(queryData)];

        forEach(queryBuilderData.value, (value, key) => {
            if (!ignoredKeys.includes(key)) {
                queryData[key] = defaultFilters[key];
            }
        });

        queryBuilderData.value = queryData;
    };

    const onPageChange = (value: number) => {
        queryBuilderData.value.page = value;
    };

    const onPerPageChange = (value: number) => {
        queryBuilderData.value.per_page = value;
        queryBuilderData.value.page = 1;
    };

    const orderByField = (field: string) => {
        let direction = queryBuilderData.value.order_by_direction == 'asc' ? 'desc' : 'asc';

        if (queryBuilderData.value.order_by != field) {
            direction = 'asc';
        }

        queryBuilderData.value.order_by = field;
        queryBuilderData.value.order_by_direction = direction;
    };

    const resetOrderBy = () => {
        queryBuilderData.value.order_by = '';
        queryBuilderData.value.order_by_direction = '';
    };

    const onTrashedChange = (value: string) => {
        queryBuilderData.value.trashed = value;
    };

    return {
        openFilter,
        queryBuilderData,
        canBeReset,
        resetQuery,
        onPageChange,
        onPerPageChange,
        orderByField,
        resetOrderBy,
        onTrashedChange,
        openModal,
        closeModal,
    };
}
