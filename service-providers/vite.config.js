import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/styles.css', 'resources/css/font.css', 'resources/css/fontawesome.min.css', 'resources/js/app.js', 'resources/js/data.js', 'resources/js/navigation.js', 'resources/js/search.js'],
            refresh: true,
        }),
    ],
});
