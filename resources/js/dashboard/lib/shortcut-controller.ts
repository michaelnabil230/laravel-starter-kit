import Mousetrap, { ExtendedKeyboardEvent } from 'mousetrap';
import 'mousetrap/plugins/pause/mousetrap-pause.js';

export class ShortcutController {
    addShortcut(keys: string | string[], callback: (e: ExtendedKeyboardEvent, combo: string) => boolean | void): void {
        Mousetrap.bind(keys, callback);
    }

    disableShortcut(keys: string | string[]): void {
        Mousetrap.unbind(keys);
    }

    pauseShortcuts(): void {
        Mousetrap.pause();
    }

    resumeShortcuts(): void {
        Mousetrap.unpause();
    }

    save(callback: VoidFunction): void {
        this.addShortcut('mod+s', (e: ExtendedKeyboardEvent) => {
            e.preventDefault();
            callback();
        });
    }
}

export const shortcutControllerKey = Symbol('shortcut-controller');

export const shortcutController = new ShortcutController();
