import { ExtendedKeyboardEvent } from 'mousetrap';
import { Toast } from './toast';

export interface DashboardAppInterface {
    log(message: string, type: 'log' | 'error' | 'warn' | 'info'): void;

    $on(event: string, listener: (...args: any[]) => void): void;

    $once(event: string, listener: (...args: any[]) => void): void;

    $off(event: string, listener: (...args: any[]) => void): void;

    $emit(event: string, ...args: any[]): void;

    config<T>(key: keyof AppConfig): T;

    add(message: string, type: Toast['type']): void;

    success(message: string): void;

    danger(message: string): void;

    info(message: string): void;

    warning(message: string): void;

    remove(id: string): void;

    clear(): void;

    addShortcut(
        keys: string | string[],
        callback: (event: ExtendedKeyboardEvent, combo: string) => boolean | void,
    ): void;

    disableShortcut(keys: string | string[]): void;

    pauseShortcuts(): void;

    resumeShortcuts(): void;
}

export interface AppConfig {
    appName: string;
    timezone: string;
    locale: string;
    translations: Translations;
    supportedLocales: Locales;
    sentry: Sentry;
    environment: 'local' | 'development' | 'production';
}

export interface Translations {
    [key: string]: any;
}

export interface Locales {
    [name: string]: Locale;
}

export interface Locale {
    name: string;
    native: string;
    regional: string;
    script: string;
    url: string;
}

export interface Sentry {
    dsn: any;
    release: any;
    environment: any;
    sendDefaultPii: any;
    tracesSampleRate: any;
}
