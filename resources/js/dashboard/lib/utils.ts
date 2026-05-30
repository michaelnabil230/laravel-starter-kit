import useLocalization from '@/dashboard/composables/useLocalization';
import { clsx, type ClassValue } from 'clsx';
import dayjs from 'dayjs';
import { twMerge } from 'tailwind-merge';

export const cn = (...inputs: ClassValue[]) => {
    return twMerge(clsx(inputs));
};

export const humanizeDuration = (minutes: number): string => {
    const { __ } = useLocalization();

    const duration = dayjs.duration(minutes, 'minutes');

    const format = (value: number, singular: string, plural: string): string | null =>
        value ? `${value} ${value === 1 ? __(singular) : __(plural)}` : null;

    return [
        format(duration.hours(), 'global.time.hour', 'global.time.hours'),
        format(duration.minutes(), 'global.time.minute', 'global.time.minutes'),
    ]
        .filter(Boolean)
        .join(` ${__('global.and')} `);
};
