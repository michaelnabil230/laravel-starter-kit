import { shortcutController, ShortcutController, shortcutControllerKey } from '@/dashboard/lib/shortcut-controller';
import { inject } from 'vue';

export default function useShortcutController(): ShortcutController {
    return inject(shortcutControllerKey, shortcutController);
}
