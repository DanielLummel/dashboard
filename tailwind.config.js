import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Space Grotesk', 'Avenir Next', 'Segoe UI', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', 'Fira Code', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                brand: {
                    50: '#ecfeff',
                    100: '#cffafe',
                    500: '#14b8a6',
                    600: '#0d9488',
                    700: '#0f766e',
                },
                accent: {
                    100: '#ffedd5',
                    500: '#f97316',
                    600: '#ea580c',
                },
            },
        },
    },

    plugins: [forms],
};
