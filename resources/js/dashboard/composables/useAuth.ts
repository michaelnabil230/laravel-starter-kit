import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export const useAuth = () => {
    const auth = computed(() => usePage().props.auth);

    return {
        auth,
    };
};
