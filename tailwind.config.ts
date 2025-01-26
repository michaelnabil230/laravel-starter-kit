import formsPlugin from '@tailwindcss/forms';
import prelinePlugin from 'preline/plugin';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './node_modules/preline/dist/*.js',
    ],
    darkMode: 'class',
    theme: {
        container: {
            padding: {
                DEFAULT: '1rem',
                sm: '2rem',
                lg: '4rem',
                xl: '5rem',
                '2xl': '6rem',
            },
        },
        extend: {
            customForms: (theme) => ({
                default: {
                    checkbox: {
                        '&:indeterminate': {
                            background:
                                "url(\"data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='8' height='2' x='4' y='7' rx='1'/%3E%3C/svg%3E\");",
                            borderColor: 'transparent',
                            backgroundColor: 'currentColor',
                            backgroundSize: '100% 100%',
                            backgroundPosition: 'center',
                            backgroundRepeat: 'no-repeat',
                        },
                    },
                },
            }),
        },
    },
    plugins: [formsPlugin, prelinePlugin],
};
