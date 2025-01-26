import { onMounted, ref } from 'vue';

type Appearance = 'light' | 'dark' | 'system';

export function updateTheme(value: Appearance) {
    if (typeof window === 'undefined') {
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

const mediaQuery = () => {
    if (typeof window === 'undefined') {
        return null;
    }

    return window.matchMedia('(prefers-color-scheme: dark)');
};

const getStoredAppearance = () => {
    if (typeof window === 'undefined') {
        return null;
    }

    return localStorage.getItem('appearance') as Appearance | null;
};

const handleSystemThemeChange = () => {
    const currentAppearance = getStoredAppearance();

    updateTheme(currentAppearance || 'system');
};

export function initializeTheme() {
    if (typeof window === 'undefined') {
        return;
    }

    const savedAppearance = getStoredAppearance();
    updateTheme(savedAppearance || 'system');

    mediaQuery()?.addEventListener('change', handleSystemThemeChange);
}

export function useAppearance() {
    const appearance = ref<Appearance>('system');

    onMounted(() => {
        initializeTheme();

        const savedAppearance = localStorage.getItem('appearance') as Appearance | null;

        if (savedAppearance) {
            appearance.value = savedAppearance;
        }
    });

    function updateAppearance(value: Appearance) {
        appearance.value = value;

        localStorage.setItem('appearance', value);

        updateTheme(value);
    }

    return {
        appearance,
        updateAppearance,
    };
}
