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
            colors: {
                blue: {
                    DEFAULT: '#121f48', // Esto aplica para bg-blue, text-blue, etc.
                    // Opcional: Si usas números en tus clases (ej. bg-blue-500 o bg-blue-600)
                    // puedes mapearlos al mismo color para que no se rompa tu diseño actual.
                    500: '#121f48',
                    600: '#121f48',
                    700: '#0d1633', // Un tono un poco más oscuro por si necesitas un efecto "hover"
                }
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};