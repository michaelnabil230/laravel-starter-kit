import { ref } from 'vue';

export default function useGeolocation() {
    const coords = ref<GeolocationCoordinates | null>(null);
    const error = ref<string | null>(null);

    function locate(): Promise<GeolocationCoordinates> {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                error.value = 'Geolocation is not supported by this browser.';

                reject(error.value);

                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position: GeolocationPosition) => {
                    coords.value = position.coords;

                    resolve(coords.value);
                },
                (err: GeolocationPositionError) => {
                    error.value = err.message;

                    reject(error.value);
                },
                { enableHighAccuracy: true },
            );
        });
    }

    return { locate, coords, error };
}
