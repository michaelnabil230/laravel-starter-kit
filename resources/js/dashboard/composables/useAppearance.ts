import { setCookie } from '@/dashboard/lib/cookie';
import { onMounted, ref } from 'vue';

type Appearance = 'light' | 'dark' | 'system';

export function updateTheme(value: Appearance) {
    const systemTheme = mediaQuery().matches ? 'dark' : 'light';
    const desiredTheme = value === 'system' ? systemTheme : value;
    const currentTheme = getCurrentTheme();

    if (currentTheme === desiredTheme) {
        return;
    }

    const toggleMode = () => {
        if (value === 'system') {
            const systemTheme = mediaQuery()?.matches ? 'dark' : 'light';

            document.documentElement.classList.toggle('dark', systemTheme === 'dark');
        } else {
            document.documentElement.classList.toggle('dark', value === 'dark');
        }
    };

    if (typeof document.startViewTransition === 'function') {
        document.startViewTransition(toggleMode);
    } else {
        toggleMode();
    }
}

const getCurrentTheme = (): Appearance => {
    return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
};

const mediaQuery = () => {
    return window.matchMedia('(prefers-color-scheme: dark)');
};

const getStoredAppearance = () => {
    return localStorage.getItem('appearance') as Appearance | null;
};

const handleSystemThemeChange = () => {
    const currentAppearance = getStoredAppearance();

    updateTheme(currentAppearance || 'system');
};

export const initializeTheme = () => {
    const savedAppearance = getStoredAppearance();
    updateTheme(savedAppearance || 'system');

    mediaQuery().addEventListener('change', handleSystemThemeChange);
};

export function useAppearance() {
    const appearance = ref<Appearance>('system');

    onMounted(() => {
        const savedAppearance = localStorage.getItem('appearance') as Appearance | null;

        if (savedAppearance) {
            appearance.value = savedAppearance;
        }
    });

    function updateAppearance(value: Appearance) {
        appearance.value = value;

        localStorage.setItem('appearance', value);

        setCookie('appearance', value);

        updateTheme(value);
    }

    return {
        appearance,
        updateAppearance,
    };
}
