// tailwind.config.js
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
                // Ubuntu definida como a principal do sistema (Sans)
                sans: ['Ubuntu', ...defaultTheme.fontFamily.sans],

                // Montserrat disponível via classe 'font-montserrat' ou para inputs
                montserrat: ['Montserrat', ...defaultTheme.fontFamily.sans],

                // JetBrains Mono para dados técnicos, financeiros e números
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
        },
    },

    plugins: [forms],
};