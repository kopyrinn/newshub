// Node.js utility
import path from 'path'
import fs from 'fs'
import { fileURLToPath } from 'url'

// Vite
import { createServer } from 'vite'

// Express
import express from 'express'

// eslint-disable-next-line no-undef
const isProd = process.env.NODE_ENV === 'production'
console.log(isProd)
// Helpers
const __dirname = path.dirname(fileURLToPath(import.meta.url))
const resolve = (p) => path.resolve(__dirname, p)

const getIndexHTML = async () => {
    const indexHTML = isProd
        ? resolve('../../dist/client/index.html')
        : resolve('../../index.html')
    const html = await fs.promises.readFile(indexHTML, 'utf-8')
    return html
}

async function start() {
    const manifest = isProd
        ? JSON.parse(fs.readFileSync(resolve('../../dist/client/ssr-manifest.json'), 'utf-8'))
        : null

    const app = express()
    const router = express.Router()

    let vite = null
    if (isProd) {
        const clientDistPath = resolve('../../dist/client')
        // app.use('/assets', express.static(clientDistPath + '/assets'));
        app.use(express.static(clientDistPath, { index: false }))
    } else {
        vite = await createServer({
            // eslint-disable-next-line no-undef
            root: process.cwd(),
            server: { middlewareMode: true },
            appType: 'custom'
        })

        app.use(vite.middlewares)
    }

    router.get('/*', async (req, res, next) => {
        try {
            const url = req.url
            let template = await getIndexHTML()

            let render = null
            if (isProd) {
                render = (await import('../../dist/server/main-server.js')).render
            } else {
                template = await vite.transformIndexHtml(url, template)
                render = (await vite.ssrLoadModule(resolve('./main-server.js'))).render
            }

            let locale = 'ru'
            if (url.startsWith('/en/')) {
                locale = 'en'
            } else if (url.startsWith('/kk/')) {
                locale = 'kk'
            }

            let route = locale == 'ru'? url.slice(1): url.slice(4)

            if (route.startsWith('feed') || route.endsWith('notifications') || route.endsWith('actions') || route.endsWith('package') || route.endsWith('workspace') || route.endsWith('login') || route.startsWith('verify')) {
                const html = template.replace(`{LOCALE}`, locale)
                return res.status(200).set({ 'Content-Type': 'text/html' }).end(html)
            }

            const [appHtml, preloadLinks, headPayload, stateHtml] = await render(url, manifest)

            Object.entries(headPayload).forEach(([key, value]) => {
                template = template.replace(`<!--${key}-->`, value)
            })

            const html = template
                .replace(`<!--preload-links-->`, preloadLinks)
                .replace('<!--app-html-->', appHtml)
                .replace('<!--store-state-->', stateHtml)
                .replace(`{LOCALE}`, locale)
                .replaceAll(`{URL}`, 'https://newshub.kz/')

            res.status(200).set({ 'Content-Type': 'text/html' }).end(html)
        } catch (e) {
            if (vite) {
                vite.ssrFixStacktrace(e)
            }

            next(e)
        }
    })

    // Routes
    app.use('/', router)

    app.listen(3000, () => {
        console.log('Server is listen on port 3000')
    })
}

start()