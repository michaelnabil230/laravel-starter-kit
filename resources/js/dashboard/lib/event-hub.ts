import { TinyEmitter } from 'tiny-emitter';

export class EventHub {
    private emitter: TinyEmitter;

    constructor() {
        this.emitter = new TinyEmitter();
    }

    $on(event: string, listener: (...args: any[]) => void): void {
        this.emitter.on(event, listener);
    }

    $once(event: string, listener: (...args: any[]) => void): void {
        this.emitter.once(event, listener);
    }

    $off(event: string, listener: (...args: any[]) => void): void {
        this.emitter.off(event, listener);
    }

    $emit(event: string, ...args: any[]): void {
        this.emitter.emit(event, ...args);
    }
}

export const eventHubKey = Symbol('event-hub');

export const eventHub = new EventHub();
