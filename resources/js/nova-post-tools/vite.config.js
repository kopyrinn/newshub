import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig({
    define: {
        'process.env.NODE_ENV': JSON.stringify('production'),
    },
    plugins: [
        vue(),
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/tinymce/skins/ui/oxide',
                    dest: 'skins/ui',
                },
                {
                    src: 'node_modules/tinymce/skins/ui/oxide-dark',
                    dest: 'skins/ui',
                },
                {
                    src: 'node_modules/tinymce/skins/content/default',
                    dest: 'skins/content',
                },
                {
                    src: 'node_modules/tinymce/skins/content/dark',
                    dest: 'skins/content',
                },
            ],
        }),
    ],
    publicDir: false,
    build: {
        outDir: fileURLToPath(new URL('../../../public/assets/nova-post-tools', import.meta.url)),
        emptyOutDir: true,
        lib: {
            entry: fileURLToPath(new URL('./field.js', import.meta.url)),
            name: 'NewsHubPostTools',
            formats: ['iife'],
            fileName: () => 'field.js',
        },
        rollupOptions: {
            output: {
                assetFileNames: 'field.[ext]',
            },
        },
    },
})
