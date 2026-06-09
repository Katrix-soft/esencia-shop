import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "on-secondary-container": "#5e5548",
                "surface-dim": "#dbd7cf",
                "on-tertiary": "#ffffff",
                "primary": "#4a7c59",
                "surface-bright": "#faf6f0",
                "on-primary-container": "#d8f0de",
                "on-background": "#2e3230",
                "error": "#b83230",
                "inverse-on-surface": "#f5f0e8",
                "tertiary-fixed": "#f8e0a8",
                "outline-variant": "#c4c8bc",
                "surface-container-lowest": "#ffffff",
                "on-secondary": "#ffffff",
                "surface-container-low": "#f5f1ea",
                "on-primary-fixed-variant": "#2a6038",
                "surface-tint": "#4a7c59",
                "tertiary-fixed-dim": "#dcc48e",
                "on-primary-fixed": "#002110",
                "on-error-container": "#690005",
                "outline": "#74796e",
                "on-secondary-fixed-variant": "#4a4538",
                "on-primary": "#ffffff",
                "on-error": "#ffffff",
                "error-container": "#ffdad8",
                "primary-container": "#78a886",
                "secondary-container": "#f0e8db",
                "surface-container-high": "#eae6de",
                "secondary": "#6b6358",
                "on-surface": "#2e3230",
                "surface-container-highest": "#e4e0d8",
                "surface": "#faf6f0",
                "secondary-fixed": "#f0e8db",
                "on-tertiary-fixed": "#221a05",
                "secondary-fixed-dim": "#d4ccbf",
                "primary-fixed-dim": "#8ecf9e",
                "on-tertiary-container": "#554020",
                "tertiary": "#705c30",
                "background": "#faf6f0",
                "inverse-surface": "#2e3230",
                "tertiary-container": "#c4a66a",
                "on-tertiary-fixed-variant": "#554020",
                "on-secondary-fixed": "#1e1a13",
                "inverse-primary": "#8ecf9e",
                "surface-variant": "#e4e0d8",
                "primary-fixed": "#c8e8d0",
                "on-surface-variant": "#4a4e4a",
                "surface-container": "#f0ece4"
            },
            borderRadius: {
                "DEFAULT": "0.5rem",
                "lg": "1rem",
                "xl": "1.5rem",
                "2xl": "2rem",
                "full": "9999px"
            },
            fontFamily: {
                "headline": ["Literata", "serif"],
                "display": ["Literata", "serif"],
                "body": ["Nunito Sans", "sans-serif"],
                "label": ["Nunito Sans", "sans-serif"],
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/container-queries')
    ],
};
