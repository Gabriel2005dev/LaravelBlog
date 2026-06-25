import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
              fontSize: {
                '2xs': '10px',
            },
            borderRadius: {
                '4xl': '2rem',      // 32px
                '5xl': '2.5rem',    // 40px
                '6xl': '3rem',      // 48px
            },
        },
    },

    plugins: [forms],
};
