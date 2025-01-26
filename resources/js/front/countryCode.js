export default {
    countryCode: null,
    async init() {
        this.countryCode = await this.geoIpLookup();
    },
    async geoIpLookup() {
        const cachedCountryCode = this.getWithExpiry('country_code');
        if (cachedCountryCode) {
            return cachedCountryCode;
        }

        let countryCode;

        try {
            const response = await fetch('https://ipapi.co/json');
            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }
            const data = await response.json();
            countryCode = data.country_code ?? 'US';
        } catch (error) {
            countryCode = 'US';
        }

        this.setWithExpiry('country_code', countryCode, 24 * 60 * 60 * 1000); // 24 hours

        return countryCode;
    },
    setWithExpiry(key, value, ttl) {
        const now = new Date();
        const item = {
            value: value,
            expiry: now.getTime() + ttl,
        };
        localStorage.setItem(key, JSON.stringify(item));
    },
    getWithExpiry(key) {
        const itemStr = localStorage.getItem(key);

        if (!itemStr) {
            return null;
        }

        const item = JSON.parse(itemStr);
        const now = new Date();

        if (now.getTime() > item.expiry) {
            localStorage.removeItem(key);
            return null;
        }

        return item.value;
    },
};
