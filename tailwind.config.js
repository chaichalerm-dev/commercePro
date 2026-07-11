import defaultTheme from 'tailwindcss/defaultTheme';
import colors from 'tailwindcss/colors';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    // Class strategy so a future theme toggle can flip dark mode per-user.
    darkMode: 'class',

    theme: {
        extend: {
            colors: {
                // Brand color; swap this single mapping to re-theme the shop.
                primary: colors.orange,
            },
            fontFamily: {
                sans: ['Figtree', 'Noto Sans Thai', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
