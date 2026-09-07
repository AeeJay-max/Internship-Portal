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
                sans:  ['Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['Georgia', ...defaultTheme.fontFamily.serif],
            },

            colors: {
                MOSRAC: {
                    navy:    '#005A2B',
                    crimson: '#00421F',
                    surface: '#f0f4f8',
                    dark:    '#00421F',
                    forest:  '#111A13',
                    hover:   '#1C2A1E',
                },
                emerald: {
                    50:  '#F0FDF4',
                    100: '#DCFCE7',
                    200: '#BBF7D0',
                    300: '#86EFAC',
                    400: '#4ADE80',
                    500: '#22C55E',
                    600: '#16A34A',
                    700: '#005A2B',
                    800: '#00421F',
                    900: '#111A13',
                    950: '#0B120C',
                },
                green: {
                    50:  '#F0FDF4',
                    100: '#DCFCE7',
                    200: '#BBF7D0',
                    300: '#86EFAC',
                    400: '#4ADE80',
                    500: '#22C55E',
                    600: '#16A34A',
                    700: '#005A2B',
                    800: '#00421F',
                    900: '#111A13',
                    950: '#0B120C',
                },
            },

            fontSize: {
                'xs':   ['0.75rem',   { lineHeight: '1.4',  letterSpacing: '0.02em' }],
                'sm':   ['0.875rem',  { lineHeight: '1.5' }],
                'base': ['1rem',      { lineHeight: '1.75' }],
                'lg':   ['1.125rem',  { lineHeight: '1.6' }],
                'xl':   ['1.25rem',   { lineHeight: '1.5' }],
                '2xl':  ['1.5rem',    { lineHeight: '1.3' }],
                '3xl':  ['1.875rem',  { lineHeight: '1.25' }],
                '4xl':  ['2.25rem',   { lineHeight: '1.2', letterSpacing: '-0.02em' }],
                '5xl':  ['3rem',      { lineHeight: '1.15', letterSpacing: '-0.025em' }],
            },

            borderRadius: {
                'sm':  '4px',
                DEFAULT: '8px',
                'md':  '12px',
                'lg':  '16px',
                'xl':  '24px',
                '2xl': '32px',
            },

            boxShadow: {
                'sm':   '0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.06)',
                DEFAULT:'0 4px 12px rgba(0,0,0,0.08), 0 2px 4px rgba(0,0,0,0.06)',
                'lg':   '0 10px 30px rgba(0,0,0,0.10), 0 4px 8px rgba(0,0,0,0.06)',
                'xl':   '0 20px 50px rgba(0,0,0,0.12), 0 8px 16px rgba(0,0,0,0.06)',
            },

            spacing: {
                '18': '4.5rem',
                '22': '5.5rem',
                '26': '6.5rem',
                '30': '7.5rem',
            },

            maxWidth: {
                '8xl': '88rem',
            },

            transitionDuration: {
                '150': '150ms',
                '250': '250ms',
            },
        },
    },

    plugins: [forms],
};
