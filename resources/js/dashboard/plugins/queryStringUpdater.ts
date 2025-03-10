import { router } from '@inertiajs/vue3';
import { identity, pickBy } from 'lodash';
import qs from 'qs';
import { Plugin } from 'vue';

export const queryStringUpdater: Plugin = {
    install() {
        const updateQueryString = () => {
            const urlParams = new URLSearchParams(window.location.search);
            const entries = Object.fromEntries(urlParams.entries());

            const params = qs.stringify(pickBy(entries, identity), {
                encode: false,
                skipNulls: true,
                allowEmptyArrays: false,
            });

            router.replace({ url: params ? `?${params}` : undefined });
        };

        router.on('navigate', updateQueryString);
        router.on('finish', updateQueryString);
    },
};
