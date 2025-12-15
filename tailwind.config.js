export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'primary': '#2B6B7F',
                'primary-dark': '#1A4D5C',
                'accent': '#A4CE4E',
                'light': '#E8F4F7',
            },
            container: {
                center: true,
                padding: '1rem',
            }
        },
    },
    plugins: [],
}