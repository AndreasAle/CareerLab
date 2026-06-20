import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    // Themed color utilities built dynamically in Blade (e.g. bg-{color}-50) must be safelisted
    // so the JIT compiler doesn't purge them.
    safelist: [
        {
            pattern: /(bg|text)-(emerald|blue|indigo|violet|purple|rose|amber|teal)-(50|100|200|300|600)/,
        },
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
