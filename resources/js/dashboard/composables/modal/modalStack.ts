import ModalRoot from '@/dashboard/Components/Modal/ModalRoot.vue';
import type { ModalConfig, Modal as ModalData } from '@/dashboard/types/global';
import { mergeDataIntoQueryString, type HttpResponse, type Method, type RequestPayload } from '@inertiajs/core';
import { http, progress, router, usePage } from '@inertiajs/vue3';
import { computed, h, markRaw, nextTick, readonly, ref, type Component, type ComputedRef, type Ref } from 'vue';
import { ResponseCache } from './cache';
import { except, generateId, kebabCase, parseResponseData, sameUrlPath } from './helpers';

export interface ReloadOptions {
    only?: string[];
    except?: string[];
    method?: Method;
    data?: Record<string, unknown>;
    headers?: Record<string, string>;
    onStart?: () => void;
    onSuccess?: (response: HttpResponse) => void;
    onError?: (error: unknown) => void;
    onFinish?: () => void;
}

export interface VisitOptions {
    method?: Method;
    data?: RequestPayload;
    headers?: Record<string, string>;
    config?: ModalConfig;
    onClose?: () => void;
    onAfterLeave?: () => void;
    queryStringArrayFormat?: 'brackets' | 'indices';
    navigate?: boolean;
    onStart?: () => void;
    onSuccess?: (response?: HttpResponse) => void;
    onError?: (...args: unknown[]) => void;
    listeners?: Record<string, (...args: unknown[]) => void>;
    props?: Record<string, unknown>;
}

export type PrefetchOption = boolean | 'hover' | 'click' | 'mount' | Array<'hover' | 'click' | 'mount'>;

export interface PrefetchOptions {
    method?: Method;
    data?: RequestPayload;
    headers?: Record<string, string>;
    queryStringArrayFormat?: 'brackets' | 'indices';
    cacheFor?: number;
    onPrefetching?: () => void;
    onPrefetched?: () => void;
}

type EventCallback = (...args: unknown[]) => void;

const baseUrl = ref<string | null>(null);
const stack = ref<Modal[]>([]);
const localModals = ref<Record<string, { name: string; callback: (modal: Modal) => void }>>({});

let closingToBaseUrlTarget: string | null = null;

const prefetchCache = new ResponseCache<HttpResponse>();

export function prefetch(href: string, options: PrefetchOptions = {}): Promise<void> {
    if (href.startsWith('#')) {
        return Promise.resolve();
    }

    const method = options.method ?? 'get';
    const data = options.data ?? ({} as RequestPayload);
    const headers = options.headers ?? {};
    const queryStringArrayFormat = options.queryStringArrayFormat ?? 'brackets';
    const cacheFor = options.cacheFor ?? 30000;

    const [url, mergedData] = mergeDataIntoQueryString(method, href || '', data, queryStringArrayFormat);
    const cacheKey = ResponseCache.key(method, url, mergedData);

    if (prefetchCache.get(cacheKey)) {
        return Promise.resolve();
    }

    const inFlight = prefetchCache.getInFlight(cacheKey);
    if (inFlight) {
        return inFlight.then(() => {});
    }

    options.onPrefetching?.();

    const requestHeaders: Record<string, string> = {
        ...headers,
        Accept: 'text/html, application/xhtml+xml',
        'X-Requested-With': 'XMLHttpRequest',
        'X-Inertia': 'true',
        'X-Inertia-Version': usePage().version ?? '',
        'X-Inertia-Modal': generateId(),
        'X-Inertia-Modal-Base-Url': baseUrl.value ?? '',
    };

    const request = http
        .getClient()
        .request({ url, method, data: mergedData, headers: requestHeaders })
        .then((response) => {
            prefetchCache.set(cacheKey, response, cacheFor);
            options.onPrefetched?.();

            return response;
        })
        .finally(() => {
            prefetchCache.deleteInFlight(cacheKey);
        });

    prefetchCache.setInFlight(cacheKey, request);

    return request.then(() => {});
}

export class Modal {
    id: string;
    isOpen: boolean;
    shouldRender: boolean;
    listeners: Record<string, EventCallback[]>;
    component: Component | null;
    props: Ref<Record<string, unknown>>;
    response: ModalData;
    config: ModalConfig;
    onCloseCallback: (() => void) | null;
    afterLeaveCallback: (() => void) | null;
    index: ComputedRef<number>;
    onTopOfStack: ComputedRef<boolean>;
    name?: string;

    constructor(
        component: Component | null,
        response: ModalData,
        config?: ModalConfig | null,
        onClose?: (() => void) | null,
        afterLeave?: (() => void) | null,
    ) {
        this.id = response.id ?? generateId();
        this.isOpen = false;
        this.shouldRender = false;
        this.listeners = {};

        this.component = component;
        this.props = ref(response.props ?? {});
        this.response = response;
        this.config = config ?? {};
        this.onCloseCallback = onClose ?? null;
        this.afterLeaveCallback = afterLeave ?? null;

        this.index = computed(() => stack.value.findIndex((m) => m.id === this.id));
        this.onTopOfStack = computed(() => {
            if (stack.value.length < 2) {
                return true;
            }

            const modals = stack.value.map((modal) => ({ id: modal.id, shouldRender: modal.shouldRender }));

            return modals.reverse().find((modal) => modal.shouldRender)?.id === this.id;
        });
    }

    getComponentPropKeys = (): string[] => {
        if (!this.component) {
            return [];
        }

        const componentProps = (this.component as Component & { props?: string[] | Record<string, unknown> }).props;

        if (Array.isArray(componentProps)) {
            return componentProps;
        }

        return componentProps ? Object.keys(componentProps) : [];
    };

    getParentModal = (): Modal | null | undefined => {
        const index = this.index.value;

        if (index < 1) {
            return null;
        }

        return stack.value
            .slice(0, index)
            .reverse()
            .find((modal) => modal.isOpen) as unknown as Modal | undefined;
    };

    getChildModal = (): Modal | null => {
        const index = this.index.value;

        if (index === stack.value.length - 1) {
            return null;
        }

        return (stack.value.slice(index + 1).find((modal) => modal.isOpen) as unknown as Modal | undefined) ?? null;
    };

    show = (): void => {
        const index = this.index.value;

        if (index > -1) {
            if (stack.value[index].isOpen) {
                return;
            }

            stack.value[index].isOpen = true;
            stack.value[index].shouldRender = true;
        }
    };

    close = (): void => {
        const index = this.index.value;

        if (index > -1) {
            if (!stack.value[index].isOpen) {
                return;
            }

            Object.keys(this.listeners).forEach((event) => {
                this.off(event);
            });

            stack.value[index].isOpen = false;
            this.onCloseCallback?.();
            this.onCloseCallback = null;
        }
    };

    setOpen = (open: boolean): void => {
        if (open) {
            this.show();
        } else {
            this.close();
        }
    };

    afterLeave = (): void => {
        const index = this.index.value;

        if (index > -1) {
            if (stack.value[index].isOpen) {
                return;
            }

            stack.value[index].shouldRender = false;
            this.afterLeaveCallback?.();
            this.afterLeaveCallback = null;
        }

        if (index === 0) {
            stack.value = [];

            const savedBaseUrl = baseUrl.value;
            baseUrl.value = null;

            closingToBaseUrlTarget = savedBaseUrl;

            if (savedBaseUrl && typeof window !== 'undefined' && !sameUrlPath(savedBaseUrl, window.location.href)) {
                router.push({
                    url: savedBaseUrl,
                    preserveScroll: true,
                    preserveState: true,
                    props: (currentProps: Record<string, unknown>) => {
                        // eslint-disable-next-line @typescript-eslint/no-unused-vars
                        const { modal, ...rest } = currentProps;

                        return { ...rest, modal: undefined } as any;
                    },
                });
            }
        }
    };

    on = (event: string, callback: EventCallback): void => {
        event = kebabCase(event);
        this.listeners[event] = this.listeners[event] ?? [];
        this.listeners[event].push(callback);
    };

    off = (event: string, callback?: EventCallback): void => {
        event = kebabCase(event);
        if (callback) {
            this.listeners[event] = this.listeners[event]?.filter((cb) => cb !== callback) ?? [];
        } else {
            delete this.listeners[event];
        }
    };

    emit = (event: string, ...args: unknown[]): void => {
        this.listeners[kebabCase(event)]?.forEach((callback) => callback(...args));
    };

    registerEventListenersFromAttrs = ($attrs: Record<string, unknown>): (() => void) => {
        const unsubscribers: (() => void)[] = [];

        Object.keys($attrs)
            .filter((key) => key.startsWith('on'))
            .forEach((key) => {
                const eventName = kebabCase(key).replace(/^on-/, '');
                const callback = $attrs[key] as EventCallback;
                this.on(eventName, callback);
                unsubscribers.push(() => this.off(eventName, callback));
            });

        return () => unsubscribers.forEach((unsub) => unsub());
    };

    reload = (options: ReloadOptions = {}): void => {
        let keys = Object.keys(this.response.props);

        if (options.only) {
            keys = options.only;
        }

        if (options.except) {
            keys = except(keys, options.except) as string[];
        }

        if (!this.response?.url) {
            return;
        }

        const method = options.method ?? 'get';
        const data = options.data ?? {};

        options.onStart?.();

        http.getClient()
            .request({
                url: this.response.url,
                method,
                data: method === 'get' ? undefined : data,
                params: method === 'get' ? data : undefined,
                headers: {
                    ...(options.headers ?? {}),
                    Accept: 'text/html, application/xhtml+xml',
                    'X-Inertia': 'true',
                    'X-Inertia-Partial-Component': this.response.component,
                    'X-Inertia-Version': this.response.version ?? '',
                    'X-Inertia-Partial-Data': keys.join(','),
                    'X-Inertia-Modal': generateId(),
                    'X-Inertia-Modal-Base-Url': baseUrl.value ?? '',
                },
            })
            .then((response) => {
                this.updateProps((parseResponseData(response.data) as ModalData).props);
                options.onSuccess?.(response);
            })
            .catch((error: unknown) => {
                options.onError?.(error);
            })
            .finally(() => {
                options.onFinish?.();
            });
    };

    updateProps = (props: Record<string, unknown>): void => {
        Object.assign(this.props.value, props);
    };
}

function registerLocalModal(name: string, callback: (modal: Modal) => void): void {
    localModals.value[name] = { name, callback };
}

function pushLocalModal(
    name: string,
    config?: ModalConfig | null,
    onClose?: (() => void) | null,
    afterLeave?: (() => void) | null,
    props?: Record<string, unknown> | null,
): Modal {
    if (!localModals.value[name]) {
        throw new Error(`The local modal "${name}" has not been registered.`);
    }

    const responseData = { props: props ?? {} } as ModalData;
    const modal = push(null, responseData, config, onClose, afterLeave);
    modal.name = name;
    localModals.value[name].callback(modal);

    return modal;
}

function isValidModalResponse(data: unknown): data is ModalData {
    return (
        typeof data === 'object' &&
        data !== null &&
        'component' in data &&
        typeof (data as ModalData).component === 'string'
    );
}

function updateBrowserUrl(url: string | undefined, useBrowserHistory: boolean, modalData?: ModalData): void {
    if (!url || !useBrowserHistory || typeof window === 'undefined') {
        return;
    }

    router.push({
        url,
        preserveScroll: true,
        preserveState: true,
        props: modalData
            ? (((currentProps: Record<string, unknown>) => ({
                  ...currentProps,
                  modal: {
                      ...modalData,
                      baseUrl: baseUrl.value,
                  },
              })) as any)
            : undefined,
    });
}

function pushFromResponseData(
    responseData: ModalData,
    config: ModalConfig = {},
    onClose: (() => void) | null = null,
    onAfterLeave: (() => void) | null = null,
): Promise<Modal> {
    if (!isValidModalResponse(responseData)) {
        return Promise.reject(
            new Error(
                'Invalid modal response. This usually happens when the server returns a redirect (e.g., due to session expiration).',
            ),
        );
    }

    return router
        .resolveComponent(responseData.component)
        .then((component) =>
            push(
                markRaw(component as Component),
                responseData,
                { ...responseData.config, ...config },
                onClose,
                onAfterLeave,
            ),
        );
}

function visit(
    href: string,
    method: Method,
    payload: RequestPayload = {},
    headers: Record<string, string> = {},
    config: ModalConfig = {},
    onClose: (() => void) | null = null,
    onAfterLeave: (() => void) | null = null,
    queryStringArrayFormat: 'brackets' | 'indices' = 'brackets',
    useBrowserHistory: boolean = false,
    onStart: (() => void) | null = null,
    onSuccess: ((response?: HttpResponse) => void) | null = null,
    onError: ((...args: unknown[]) => void) | null = null,
    props: Record<string, unknown> | null = null,
): Promise<Modal> {
    const modalId = generateId();

    return new Promise((resolve, reject) => {
        if (href.startsWith('#')) {
            resolve(pushLocalModal(href.substring(1), config, onClose, onAfterLeave, props));

            return;
        }

        const [url, data] = mergeDataIntoQueryString(method, href || '', payload, queryStringArrayFormat);

        const cachedResponse = prefetchCache.get(ResponseCache.key(method, url, data));
        if (cachedResponse) {
            const cachedData = parseResponseData(cachedResponse.data) as ModalData;
            onSuccess?.(cachedResponse);
            pushFromResponseData(cachedData, config, onClose, onAfterLeave)
                .then((modal) => {
                    updateBrowserUrl(cachedData.url, useBrowserHistory, cachedData);
                    resolve(modal);
                })
                .catch(reject);

            return;
        }

        if (stack.value.length === 0) {
            baseUrl.value = typeof window !== 'undefined' ? window.location.href : '';
        }

        const requestHeaders: Record<string, string> = {
            ...headers,
            Accept: 'text/html, application/xhtml+xml',
            'X-Requested-With': 'XMLHttpRequest',
            'X-Inertia': 'true',
            'X-Inertia-Version': usePage().version ?? '',
            'X-Inertia-Modal': modalId,
            'X-Inertia-Modal-Base-Url': baseUrl.value ?? '',
        };

        onStart?.();

        progress?.start();

        http.getClient()
            .request({ url, method, data, headers: requestHeaders })
            .then((response) => {
                const responseData = parseResponseData(response.data) as ModalData;
                onSuccess?.(response);
                pushFromResponseData(responseData, config, onClose, onAfterLeave)
                    .then((modal) => {
                        updateBrowserUrl(responseData.url, useBrowserHistory, responseData);
                        resolve(modal);
                    })
                    .catch(reject);
            })
            .catch((...args: unknown[]) => {
                onError?.(...args);
                reject(args[0]);
            })
            .finally(() => {
                progress?.finish();
            });
    });
}

function loadDeferredProps(modal: Modal): void {
    const deferred = modal.response?.meta?.deferredProps;

    if (!deferred) {
        return;
    }

    Object.keys(deferred).forEach((key) => {
        modal.reload({ only: deferred[key] });
    });
}

function push(
    component: Component | null,
    response: ModalData,
    config?: ModalConfig | null,
    onClose?: (() => void) | null,
    afterLeave?: (() => void) | null,
): Modal {
    const newModal = new Modal(component, response, config, onClose, afterLeave);
    // @ts-expect-error Vue reactivity transforms the Modal instance
    stack.value.push(newModal);
    loadDeferredProps(newModal);

    nextTick(() => newModal.show());

    return newModal;
}

export const modalPropNames = ['size', 'position', 'slideover'];

export const renderApp = (App: Component, props: any): (() => ReturnType<typeof h>) => {
    return () => h(ModalRoot, () => h(App, props));
};

export function visitModal(url: string, options: VisitOptions = {}): Promise<Modal> {
    return useModalStack()
        .visit(
            url,
            options.method ?? 'get',
            options.data ?? {},
            options.headers ?? {},
            options.config ?? {},
            options.onClose,
            options.onAfterLeave,
            options.queryStringArrayFormat ?? 'brackets',
            options.navigate ?? false,
            options.onStart,
            options.onSuccess,
            options.onError,
            options.props ?? null,
        )
        .then((modal) => {
            const listeners = options.listeners ?? {};

            Object.keys(listeners).forEach((event) => {
                const eventName = kebabCase(event);
                modal.on(eventName, listeners[event]);
            });

            return modal;
        });
}

export interface ModalStack {
    getBaseUrl: () => string | null;
    setBaseUrl: (url: string | null) => void;
    isClosingToBaseUrl: (pageUrl: string) => boolean;
    clearClosingToBaseUrl: () => void;
    stack: Readonly<Ref<readonly Modal[]>>;
    push: typeof push;
    pushFromResponseData: typeof pushFromResponseData;
    closeAll: (force?: boolean) => void;
    reset: () => void;
    visit: typeof visit;
    registerLocalModal: typeof registerLocalModal;
    removeLocalModal: (name: string) => boolean;
}

export function useModalStack(): ModalStack {
    return {
        getBaseUrl: () => baseUrl.value,
        setBaseUrl: (url: string | null) => (baseUrl.value = url),
        isClosingToBaseUrl: (pageUrl: string) => {
            if (!closingToBaseUrlTarget) return false;
            const targetPath = new URL(closingToBaseUrlTarget, 'http://x').pathname;
            const pagePath = new URL(pageUrl, 'http://x').pathname;

            return targetPath === pagePath;
        },
        clearClosingToBaseUrl: () => (closingToBaseUrlTarget = null),
        stack: readonly(stack) as unknown as Readonly<Ref<readonly Modal[]>>,
        push,
        pushFromResponseData,
        closeAll: (force = false) => {
            if (force) {
                stack.value = [];
            } else {
                [...stack.value].reverse().forEach((modal) => modal.close());
            }
        },
        reset: () => (stack.value = []),
        visit,
        registerLocalModal,
        removeLocalModal: (name: string) => delete localModals.value[name],
    };
}
