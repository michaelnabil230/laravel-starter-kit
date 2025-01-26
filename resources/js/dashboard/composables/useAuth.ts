import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export const useAuth = () => {
    return computed(() => usePage().props.auth);
};
