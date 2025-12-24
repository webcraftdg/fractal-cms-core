const defaultTheme = require('tailwindcss/defaultTheme');
const yiiConf = require('./webpack-yii.json');
const sourcePath = yiiConf.sourceDir + '/' + yiiConf.subDirectories.sources
const colors = require('tailwindcss/colors');
module.exports = {
    darkMode: 'class',
    content: [
        sourcePath + '/app/**/*.{html,ts}',
        sourcePath + '/**/*.{html,ts,tsx,js,jsx}',
        './src/views/**/*.php',
        './src/widgets/views/*.php',
    ],
    theme: {
        extend: {
            colors: {
                fractal: {
                    bg: '#f8fafc',        // slate-50
                    surface: '#ffffff',
                    border: '#e5e7eb',    // gray-200
                    text: '#0f172a',      // slate-900
                    muted: '#64748b',     // slate-500

                    primary: '#2563eb',   // blue-600
                    primaryHover: '#1d4ed8',

                    success: '#16a34a',
                    successHover: '#4bae6c',
                    warning: '#d97706',
                    danger: '#dc2626',
                }
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
                mono: ['JetBrains Mono', 'monospace'],
            },
        },
    },
    plugins: [require('@tailwindcss/forms', {
        // strategy: 'base', // only generate global styles
        // strategy: 'class', // only generate classes
    })],
};
