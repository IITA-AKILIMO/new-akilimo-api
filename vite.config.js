import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/scss/welcome.scss',
                'resources/scss/playground.scss',
                'resources/js/app.tsx',
                'resources/js/playground/index.tsx',
            ],
            refresh: true,
        }),
        react(),
    ],
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: 'manifest.json',
        rollupOptions: {
            output: {
                assetFileNames: '[name][extname]',
            },
        },
    },
});
