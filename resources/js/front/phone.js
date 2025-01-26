import { parsePhoneNumberWithError } from 'libphonenumber-js';
import countries from '../utilities/countries.json';

export default (model, initValue = null, initCountryCode = null) => ({
    selectedCountry: countries[0],
    search: '',
    activeIndex: null,
    show: false,
    model: model,
    phone: '',
    async init() {
        this.watch();

        try {
            this.phone = parsePhoneNumberWithError(initValue).nationalNumber;
        } catch (error) {}

        let selectedCountry;

        if (initCountryCode === null) {
            await this.$store.countryCode.init();
            selectedCountry = countries.find((country) => country.code === this.$store.countryCode.countryCode);
        } else {
            selectedCountry = countries.find((country) => country.code === initCountryCode);
        }

        this.choose(selectedCountry ?? countries[0]);
    },
    watch() {
        this.$watch('show', (value) => {
            this.activeIndex = null;
            this.search = '';
        });

        this.$watch('phone', (value) => {
            try {
                this.phone = parsePhoneNumber(value, this.selectedCountry.dialCode).nationalNumber;
            } catch (error) {
                return;
            }
        });

        document.addEventListener('reset', (event) => {
            if (event.target.contains(this.$el)) {
                this.reset();
            }
        });
    },
    toggle() {
        this.show = !this.show;
    },
    close() {
        this.show = false;
    },
    choose(country) {
        this.selectedCountry = country;
        const button = this.$refs.button;
        this.$nextTick(() => button.dispatchEvent(new CustomEvent('select', { detail: { select: country } })));
        this.close();
    },
    reset() {
        this.selectedCountry = countries[0];
        this.search = '';
        this.model = null;
        this.close();
    },
    navigate(event) {
        if (!this.show && event.key === 'Tab') return;

        if (event.key !== 'ArrowUp' && event.key !== 'ArrowDown' && event.key !== 'Tab') return;

        event.preventDefault();

        const current = this.activeIndex ?? -1;
        const max = this.countries.length - 1;

        const keys = {
            ArrowUp: () => (current === 0 ? max : current - 1),
            ArrowDown: () => (current + 1) % this.countries.length,
            Tab: () => (current === max ? 0 : current + 1),
        };

        const next = keys[event.key]();

        this.$refs.list.querySelectorAll('[role="option"]').forEach((item, index) => {
            item.removeAttribute('tabindex');

            if (index === next) {
                item.setAttribute('tabindex', '0');
                item.focus();
            }
        });

        this.activeIndex = next;
    },
    onMouseEnter() {
        this.activeIndex = null;
        this.$refs.list.querySelectorAll('[role="option"]').forEach((item, index) => {
            item.removeAttribute('tabindex');
        });
    },
    normalize(string) {
        return string.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    },
    get countries() {
        if (this.search === '') return Object.values(countries);

        const search = this.normalize(this.search.toLowerCase());

        return countries.filter((country) => {
            const name = country.name;

            return (
                this.normalize(name.ar.toString().toLowerCase()).indexOf(search) !== -1 ||
                this.normalize(name.en.toString().toLowerCase()).indexOf(search) !== -1
            );
        });
    },
});
