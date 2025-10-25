import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '$': 'jQuery'
        },
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['jquery', 'alpinejs', 'flowbite', 'chart.js'],
                    fontawesome: ['@fortawesome/fontawesome-free'],
                },
            },
        },
        chunkSizeWarningLimit: 1000,
    },
});
