import { get, has } from 'lodash';
import forEach from 'lodash/forEach';
import { Translations } from '../types/appConfig';
import config from './config';

export default function __(key: string, replace?: { [key: string]: string }): string {
    let translations: Translations = config('translations');

    const keyExists = has(translations, key);

    if (!keyExists) {
        console.warn(`Translation key '${key}' is not exists in translations.`);
    }

    let translation = keyExists ? get(translations, key) : key;

    forEach(replace, (value, key) => {
        key = String(key);

        if (value === null) {
            console.error(`Translation '${translation}' for key '${key}' contains a null replacement.`);

            return;
        }

        value = String(value);

        const searches = [':' + key, ':' + key.toUpperCase(), ':' + key.charAt(0).toUpperCase() + key.slice(1)];

        const replacements = [value, value.toUpperCase(), value.charAt(0).toUpperCase() + value.slice(1)];

        for (let i = searches.length - 1; i >= 0; i--) {
            translation = translation.replace(searches[i], replacements[i]);
        }
    });

    return translation;
}
