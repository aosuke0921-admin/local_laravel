import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.jsx',
                'resources/js/pop_select.jsx',
            ],
            refresh: true,
        }),
    ],

    server: {
        host: 'localhost',
        hmr: {
            host: 'localhost',
        },
    },

    build: {
        outDir: 'public/build',
        emptyOutDir: true,
    },
});