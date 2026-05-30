import { SharedData } from '@/dashboard/types/shared-data';
import { usePage } from '@inertiajs/vue3';

export default function initialSharedData<K extends keyof SharedData>(key: K): SharedData[K] {
    const props = usePage().props;

    return props[key] as SharedData[K];
}
