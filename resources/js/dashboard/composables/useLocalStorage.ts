import { ref, watch } from 'vue';

export default function useLocalStorage<T>(key: string, defaultValue: T) {
    const storedValue = localStorage.getItem(key);

    let parsedValue: T;
    try {
        parsedValue = storedValue ? JSON.parse(storedValue) : defaultValue;
    } catch (error) {
        console.error('Error parsing localStorage data', error);
        parsedValue = defaultValue;
    }

    const value = ref<T>(parsedValue);

    watch(
        value,
        (newValue) => {
            try {
                localStorage.setItem(key, JSON.stringify(newValue));
            } catch (error) {
                console.error('Error saving to localStorage', error);
            }
        },
        { deep: true },
    );

    const remove = () => {
        localStorage.removeItem(key);
        value.value = defaultValue;
    };

    return { value, remove };
}
