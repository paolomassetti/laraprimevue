import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        watch: {
            usePolling: true,
        },
        hmr: {
            host: 'localhost',
            port: 5173,
        },
        origin: 'http://localhost:5173',
        cors: true
    },
    css: {
        preprocessorOptions: {
            sass: {
              api: 'modern-compiler',
            },
        }
    },
});
