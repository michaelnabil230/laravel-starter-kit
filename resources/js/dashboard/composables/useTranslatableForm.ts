import useInitialSharedData from '@/dashboard/composables/useInitialSharedData';
import type { LocaleKey } from '@/dashboard/types/shared-data';

type TranslatableField = Record<LocaleKey, string>;

export default function useTranslatableForm() {
    const initialSharedData = useInitialSharedData();

    const supportedLocales = initialSharedData('supportedLocales');

    const localeKeys = Object.keys(supportedLocales) as LocaleKey[];

    const makeTranslatableField = (defaultValue = ''): TranslatableField => {
        return localeKeys.reduce<Record<string, string>>((acc, locale) => {
            acc[locale] = defaultValue;
            return acc;
        }, {}) as TranslatableField;
    };

    const localeDisplayName = (locale: LocaleKey): string => supportedLocales[locale].native;

    return {
        localeKeys,
        makeTranslatableField,
        localeDisplayName,
    };
}
