import { eventHub, EventHub, eventHubKey } from '@/dashboard/lib/event-hub';
import { inject } from 'vue';

export default function useEventHub(): EventHub {
    return inject(eventHubKey, eventHub);
}
