import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import  { resolve } from 'path'

export default defineConfig({
    resolve: {
        alias: {
            /**
             * https://stackoverflow.com/a/75716822
             * solution for the `didn't resolve at build time` warning on images; we may have to put them on the resources folder but whatever, it works
             */
            $images: resolve('public/assets/images'),
            $fonts: resolve('public/assets/fonts')
        }
    },
    build: {
        buildDirectory: 'dist',
        manifest: false,
        rollupOptions: {
            output: {
                entryFileNames: 'aceada.js',
                assetFileNames: '[name].[ext]'
            }
        },
    },
    plugins: [
        laravel({
            buildDirectory: 'dist',
            input: 'resources/js/aceada.js',
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
    // https://stackoverflow.com/a/71071183/8479967
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: ``
            }
        }
    }
});
