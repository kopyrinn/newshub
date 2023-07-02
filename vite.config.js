import fs from 'fs'
// import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import { defineConfig, loadEnv, splitVendorChunkPlugin, normalizePath } from 'vite'
import path from 'node:path'
import { VitePWA } from 'vite-plugin-pwa'
import { resolve, dirname } from 'node:path'
import { fileURLToPath } from 'url'
import VueI18nPlugin from '@intlify/unplugin-vue-i18n/vite'
// import purge from '@erbelion/vite-plugin-laravel-purgecss/dist/index.mjs'
import { viteStaticCopy } from 'vite-plugin-static-copy'
import UnheadVite from '@unhead/addons/vite'
import { PurgeCSS } from 'purgecss'

export default defineConfig(({ command, mode }) => {
    const env = loadEnv(mode, process.cwd())
    const isSsr = env.VITE_SSR == 1

    const rollupOptions = {}

    if (!isSsr) {
        rollupOptions.output = {
            entryFileNames: `assets/[hash].js`,
            chunkFileNames: `assets/[hash].js`,
            assetFileNames: `assets/[hash].[ext]`
            // entryFileNames: `assets/[name].[hash].js`,
            // chunkFileNames: `assets/[name].[hash].js`,
            // assetFileNames: `assets/[name].[hash].[ext]`
        }
    }

    const plugins = [
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        VueI18nPlugin({
          include: resolve(dirname(fileURLToPath(import.meta.url)), './resources/js/locales/**'),
        }),
        UnheadVite(),
        purge({
            templates: ['blade', 'vue'],
            safelist: [
                /carousel/,
                /ProseMirror/,
                /v-lazy-image/,
                /lang-/,
                /popper/,
                /skeletor/,
                /fade-enter/,
                /fade-leave/,
                /drawer/, /el-pager/, /el-scrollbar/, /el-pagination/, /el-loading/, /circular/, /el-icon/, /el-notification/, /swal2/, /van(?!-(coupon|tag|badge|picker|button|submit|contact|popover|notify|dialog|toast|calendar|address|checkbox|radio|uploader|cascader|dropdown|password|progress|sidebar|tree|stepper|steps|step|index|number|key))/, /el-popper/, /el-scrollbar/, /el-select/, /el-input/, /el-tag/, /is-dark/, /modal/, /ql-/, /ki-/, /btn-/, /dropzone/, /dz-message/,
            ]
        }),
    ]

    if (!isSsr) {
        plugins.push(
            viteStaticCopy({
                targets: [
                    {
                    src: 'public/client',
                    dest: normalizePath(path.resolve(__dirname, './dist'))
                    },
                ]
            }),
        )

        plugins.push(
            VitePWA({
                base: "/",
                strategies: "injectManifest",
                srcDir: 'resources/js/app',
                outDir: 'dist/client',
                filename: 'sw.js',
                registerType: 'autoUpdate',
                injectManifest: {
                    globPatterns: [],
                },
                manifest: {
                    "display": "standalone",
                    "scope": "/",
                    "id": env.VITE_ORIGIN_URL,
                    "start_url": env.VITE_ORIGIN_URL,
                    "theme_color": "#12121a",
                    "background_color": "#12121a",
                    "name": "NewsHub.kz",
                    "short_name": "NewsHub.kz",
                    "description": "NewsHub.kz",
                    "handle_links": "preferred",
                    "url_handlers": [
                        {
                            "origin": env.VITE_ORIGIN_URL
                        }
                    ],
                    "icons": [
                        {
                            "src": "/icon-192x192.png",
                            "sizes": "192x192",
                            "type": "image/png"
                        },
                        {
                            "src": "/icon-256x256.png",
                            "sizes": "256x256",
                            "type": "image/png"
                        },
                        {
                            "src": "/icon-384x384.png",
                            "sizes": "384x384",
                            "type": "image/png"
                        },
                        {
                            "src": "/icon-512x512.png",
                            "sizes": "512x512",
                            "type": "image/png"
                        },
                        {
                            "platform": "macos",
                            "src": "/mac-icon-192x192.png",
                            "sizes": "192x192",
                            "type": "image/png"
                        },
                        {
                            "platform": "macos",
                            "src": "/mac-icon-256x256.png",
                            "sizes": "256x256",
                            "type": "image/png"
                        },
                        {
                            "platform": "macos",
                            "src": "/mac-icon-384x384.png",
                            "sizes": "384x384",
                            "type": "image/png"
                        },
                        {
                            "platform": "macos",
                            "src": "/mac-icon-512x512.png",
                            "sizes": "512x512",
                            "type": "image/png"
                        }
                    ]
                }
            })
        )

        plugins.push(splitVendorChunkPlugin())
    }

    rollupOptions.input = {
        main: resolve(__dirname, 'index.html'),
    }

    const build = {
        rollupOptions: rollupOptions,
        chunkSizeWarningLimit: 1000,
        copyPublicDir: false,
        outDir: './dist',
        target: 'esnext',
        emptyOutDir: true
    }

    return {
        server: detectServerConfig(env.VITE_APP_DOMAIN),
        plugins: plugins,
        mode: 'production',
        resolve: {
            alias: {
                '@': '/resources/js',
            }
        },
        build: build
    }
})

function detectServerConfig(host) {
    // let keyPath = resolve(`/www/server/panel/vhost/cert/${host}/privkey.pem`)
    // let certificatePath = resolve(`/www/server/panel/vhost/cert/${host}/fullchain.pem`)

    // if (!fs.existsSync(keyPath)) {
    //     return {}
    // }

    // if (!fs.existsSync(certificatePath)) {
    //     return {}
    // }

    return {
        hmr: {
            protocol: 'ws',
            host: 'localhost',
        },
        host: 'localhost',
        // https: {
        //     key: fs.readFileSync(keyPath),
        //     cert: fs.readFileSync(certificatePath),
        // },
        watch: {
            ignored: [
                '**/app/**',
                '**/bootstrap/**',
                '**/config/**',
                '**/database/**',
                '**/nova/**',
                '**/public/**',
                '**/resources/lang/**',
                '**/resources/views/**',
                '**/routes/**',
                '**/storage/**',
                '**/vendor/**',
                '**/old_views/**',
            ]
        }
    }
}

const getTemplatePath = function(template) {
    switch(template){
        case 'blade':
            return 'resources/views/**/*.blade.php'
        case 'svelte':
            return 'resources/{js,views}/**/*.svelte'
        case 'vue':
            return 'resources/{js,views}/**/*.vue'
        case 'react':
            return 'resources/{js,views}/**/*.{tsx,ts,jsx,js,html}'
        case 'angular':
            return 'resources/{js,views}/**/*.html'
        default:
            throw new Error(`Template ${template} is not supported`)
    }
}

const purge = (options) => {
    return {
        name: 'vite-plugin-purgecss',
        enforce: 'post',
        async generateBundle(_options, bundle) {
            const cssFiles = Object.keys(bundle).filter(key => key.endsWith('.css'))
            if (!cssFiles) return
            
            let paths = []
            options?.paths?.forEach(path => paths.push(path))
            options?.templates?.forEach(template => paths.push(getTemplatePath(template)))

            for (const file of cssFiles) {
                const purged = await new PurgeCSS().purge({
                    content: paths,
                    css: [{raw: bundle[file].source}],
                    safelist: options?.safelist || []
                })
                bundle[file].source = purged[0].css
            }
        }
    }
}
