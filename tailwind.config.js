/** @type {import("tailwindcss").Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.css",
    ],
    darkMode: "selector",
    theme: {
        extend: {
            colors: {
                lightColor: "var(--color-light)",
                darkColor: "var(--color-dark)",
            },
        },
    },
    plugins: [],
};
