const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'trendy-pink': {
                    '50': '#f8f6f9', 
                    '100': '#f2eef2', 
                    '200': '#ded4df', 
                    '300': '#c9bacc', 
                    '400': '#a187a6', 
                    '500': '#795380', 
                    '600': '#6d4b73', 
                    '700': '#5b3e60', 
                    '800': '#49324d', 
                    '900': '#3b293f'
                },
                'red-orange': {
                    '50': '#fef4f4', 
                    '100': '#fde9e9', 
                    '200': '#fac7c9', 
                    '300': '#f7a6a9', 
                    '400': '#f16368', 
                    '500': '#eb2027', 
                    '600': '#d41d23', 
                    '700': '#b0181d', 
                    '800': '#8d1317', 
                    '900': '#731013'
                }
            }
        },
        
    },

    variants: {
        opacity: ['responsive', 'hover', 'focus', 'disabled'],
    },

    plugins: [require('@tailwindcss/ui')],
};
