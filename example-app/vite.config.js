import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    base: './',

    plugins: [
        laravel({
            input: [
                'resources/react/pop_select/index.jsx',
            ],
            refresh: true,
        }),
        react(),
    ],
});