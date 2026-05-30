export function generateId(prefix: string = 'inertia_modal_'): string {
    return prefix + Math.random().toString(36).substring(2, 11);
}

function strToLowercase(key: string): string {
    return key.toLowerCase();
}

export function except<T extends Record<string, unknown>>(
    target: T | string[],
    keys: string[],
    ignoreCase = false,
): Partial<T> | string[] {
    if (ignoreCase) {
        keys = keys.map(strToLowercase);
    }

    if (Array.isArray(target)) {
        return target.filter((key) => !keys.includes(ignoreCase ? strToLowercase(key) : key));
    }

    return Object.keys(target).reduce((acc, key) => {
        if (!keys.includes(ignoreCase ? strToLowercase(key) : key)) {
            (acc as Record<string, unknown>)[key] = target[key];
        }

        return acc;
    }, {} as Partial<T>);
}

export function only<T extends Record<string, unknown>>(
    target: T | string[],
    keys: string[],
    ignoreCase = false,
): Partial<T> | string[] {
    if (ignoreCase) {
        keys = keys.map(strToLowercase);
    }

    if (Array.isArray(target)) {
        return target.filter((key) => keys.includes(ignoreCase ? strToLowercase(key) : key));
    }

    return Object.keys(target).reduce((acc, key) => {
        if (keys.includes(ignoreCase ? strToLowercase(key) : key)) {
            (acc as Record<string, unknown>)[key] = target[key];
        }

        return acc;
    }, {} as Partial<T>);
}

export function rejectNullValues<T extends Record<string, unknown>>(target: T | T[keyof T][]): Partial<T> | unknown[] {
    if (Array.isArray(target)) {
        return target.filter((item) => item !== null);
    }

    return Object.keys(target).reduce((acc, key) => {
        if (key in target && target[key] !== null) {
            (acc as Record<string, unknown>)[key] = target[key];
        }
        return acc;
    }, {} as Partial<T>);
}

export function kebabCase(string: string): string {
    if (!string) return '';

    return string
        .replace(/([A-Z]+)([A-Z][a-z])/g, '$1-$2')
        .replace(/([a-z\d])([A-Z])/g, '$1-$2')
        .replace(/[\s_]+/g, '-')
        .replace(/-+/g, '-')
        .toLowerCase();
}

export function sameUrlPath(url1: string | URL | undefined | null, url2: string | URL | undefined | null): boolean {
    if (!url1 || !url2) {
        return false;
    }
    const origin = typeof window !== 'undefined' ? window.location.origin : 'http://localhost';
    const parsed1 = typeof url1 === 'string' ? new URL(url1, origin) : url1;
    const parsed2 = typeof url2 === 'string' ? new URL(url2, origin) : url2;

    return `${parsed1.origin}${parsed1.pathname}` === `${parsed2.origin}${parsed2.pathname}`;
}

export function parseResponseData(data: unknown): unknown {
    return typeof data === 'string' ? JSON.parse(data) : data;
}
