import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/styles.css', 'resources/css/fonts.css', 'resources/css/fontawesome.min.css', 'resources/js/app.js', 'resources/js/data.js', 'resources/js/navigation.js', 'resources/js/search.js'],
            refresh: true,
        }),
    ],
});
