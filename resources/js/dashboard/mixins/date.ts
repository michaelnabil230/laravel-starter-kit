import dayjs, { Dayjs } from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import timezone from 'dayjs/plugin/timezone';
import utc from 'dayjs/plugin/utc';
import initialSharedData from './initialSharedData';

dayjs.extend(utc);
dayjs.extend(timezone);
dayjs.extend(relativeTime);

export default function date() {
    return {
        timezone(): string {
            return initialSharedData('timezone');
        },
        instance(): Dayjs {
            return dayjs.tz(this.timezone());
        },
        toHuman(): string {
            return this.instance().fromNow();
        },
        toDate(): string {
            return this.format('YYYY/MM/DD');
        },
        toDateTime(): string {
            return this.format('YYYY/MM/DD h:mm A');
        },
        format(format: string): string {
            return this.instance().format(format);
        },
    };
}
