/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'night': '#0b090a',
                'eerie-black': '#161a1d',
                'blood-red': '#660708',
                'cornell-red': '#a4161a',
                'cornell-red-2': '#ba181b',
                'imperial-red': '#e5383b',
                'silver': '#b1a7a6',
                'timberwolf': '#d3d3d3',
                'white-smoke': '#f5f3f4',
                'white': '#ffffff',
            }
        },
    },
    plugins: [],
}