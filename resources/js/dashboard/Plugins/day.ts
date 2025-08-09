import dayjs from 'dayjs';
import 'dayjs/locale/ar';
import 'dayjs/locale/en';
import customParseFormat from 'dayjs/plugin/customParseFormat';
import localizedFormat from 'dayjs/plugin/localizedFormat';
import relativeTime from 'dayjs/plugin/relativeTime';
import timezone from 'dayjs/plugin/timezone';
import utc from 'dayjs/plugin/utc';
import { App, Plugin } from 'vue';

export const day: Plugin = {
    install(app: App) {
        dayjs.extend(utc);
        dayjs.extend(timezone);
        dayjs.extend(relativeTime);
        dayjs.extend(customParseFormat);
        dayjs.extend(localizedFormat);

        app.config.globalProperties.$day = dayjs;
    },
};
