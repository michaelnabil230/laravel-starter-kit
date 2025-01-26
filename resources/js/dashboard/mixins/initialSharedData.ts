import { SharedData } from '@/dashboard/types/shared-data';

export default function initialSharedData<K extends keyof SharedData>(key: K): SharedData[K] {
    return window.initialSharedData[key] as SharedData[K];
}
