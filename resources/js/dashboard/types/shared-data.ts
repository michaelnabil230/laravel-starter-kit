export interface SharedData {
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
