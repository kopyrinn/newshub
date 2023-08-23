// Node.js
import { basename } from 'node:path'

// Vue SSR
import { createSSRApp } from 'vue'
import { renderToString } from '@vue/server-renderer'

// App

// import VueI18n from 'vue-i18n'
import { createI18n } from '../../node_modules/vue-i18n/dist/vue-i18n.node.mjs'
import App from "@/App.vue"
// import { vue3Debounce } from 'vue-debounce'
import emitter from 'tiny-emitter/instance'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import isToday from 'dayjs/plugin/isToday'
import isYesterday from 'dayjs/plugin/isYesterday'
import isTomorrow from 'dayjs/plugin/isTomorrow'
import isSameOrAfter from 'dayjs/plugin/isSameOrAfter'
import VueSnip from 'vue-snip'
import messages from '@intlify/unplugin-vue-i18n/messages'
import 'dayjs/locale/ru'
import 'dayjs/locale/kk'
import router from "@/app/router"
import { store, getCurrentLocale } from '@/app/store'
import InlineSvg from 'vue-inline-svg';
import VDate from '@/components/VDate.vue';
import AppLink from '@/components/AppLink.vue';
import { Skeletor } from 'vue-skeletor';
import { api, upload } from '@/app/api'
import { renderSSRHead } from '@unhead/ssr'
import { createHead, VueHeadMixin } from "@unhead/vue"
import 'regenerator-runtime'

export async function render(url, manifest = null) {
    global.url = url

    const app = createSSRApp(App)

    dayjs.locale(getCurrentLocale())
    dayjs.extend(isSameOrAfter)
    dayjs.extend(relativeTime)
    dayjs.extend(isToday)
    dayjs.extend(isYesterday)
    dayjs.extend(isTomorrow)
    
    const getLongLocale = () => {
        switch (getCurrentLocale()) {
            case 'ru':
                return 'ru-RU';
            case 'kk':
                return 'kk-KZ';
            default:
                return 'en-US';
        }
    }

    app.config.globalProperties.$locale = getCurrentLocale
    app.config.globalProperties.$bus = emitter
    app.config.globalProperties.$dayjs = dayjs
    app.config.globalProperties.$base = (path) => import.meta.env.VITE_ORIGIN_URL + path
    app.config.globalProperties.$url = (path) => import.meta.env.VITE_APP_URL + path
    app.config.globalProperties.$storage = (path) => {
        if (path && path.startsWith('/storage')) {
            path = path.replace('/storage', '')
        }
    
        return import.meta.env.VITE_APP_URL + '/storage/' + path
    }
    app.config.globalProperties.$media = (path) => import.meta.env.VITE_ORIGIN_URL + '/assets/media/' + path
    app.config.globalProperties.$decimal = (num, digits = 2) => Number(num).toLocaleString(getLongLocale(), { maximumFractionDigits: digits, minimumFractionDigits: digits })
    app.config.globalProperties.$math = function() {
      if (Number.EPSILON === undefined) {
          Number.EPSILON = Math.pow(2, -52);
      }
      if (Math.trunc === undefined) {
          Math.trunc = function(v) {
              return v < 0 ? Math.ceil(v) : Math.floor(v);
          };
      }
      var powers = [
          1e0,  1e1,  1e2,  1e3,  1e4,  1e5,  1e6,  1e7,
          1e8,  1e9,  1e10, 1e11, 1e12, 1e13, 1e14, 1e15,
          1e16, 1e17, 1e18, 1e19, 1e20, 1e21, 1e22
      ];
      var intpow10 = function(power) {
          if (power < 0 || power > 22) {
              return Math.pow(10, power);
          }
          return powers[power];
      };
      var isRound = function(num, decimalPlaces) {
          var p = intpow10(decimalPlaces);
          return Math.round(num * p) / p === num;
      };
      var decimalAdjust = function(type, num, decimalPlaces) {
          if (type !== 'round' && isRound(num, decimalPlaces || 0))
              return num;
          var p = intpow10(decimalPlaces || 0);
          var n = (num * p) * (1 + Number.EPSILON);
          return Math[type](n) / p;
      };
      return {
          round: function(num, decimalPlaces) {
              return decimalAdjust('round', num, decimalPlaces);
          },
          ceil: function(num, decimalPlaces) {
              return decimalAdjust('ceil', num, decimalPlaces);
          },
          floor: function(num, decimalPlaces) {
              return decimalAdjust('floor', num, decimalPlaces);
          },
          trunc: function(num, decimalPlaces) {
              return decimalAdjust('trunc', num, decimalPlaces);
          },
          toFixed: function(num, decimalPlaces) {
              return decimalAdjust('round', num, decimalPlaces).toFixed(decimalPlaces);
          }
      };
    }()
    app.config.globalProperties.$isApp = false
    app.config.globalProperties.$isWeb = true
    app.config.globalProperties.$api = api
    app.config.globalProperties.$get = (url, params = {}, isAuth = false) => {
        return api(url, isAuth, {params})
    }
    app.config.globalProperties.$post = (url, data = {}, isAuth = false) => {
        return api(url, isAuth, {method: 'POST', data})
    }
    app.config.globalProperties.$upload = upload

    const i18n = createI18n({
        legacy: true,
        locale: getCurrentLocale(),
        fallbackLocale: 'en',
        globalInjection: true,
        fallbackWarn: false,
        missingWarn: false,
        messages
    })

    const head = createHead()

    app
        .directive('debounce', {})
        .use(i18n)
        .use(VueSnip)
        .use(router)
        .use(store)
        .mixin(VueHeadMixin)
        .use(head)
        .component("inline-svg", InlineSvg)
        .component("app-link", AppLink)
        .component("VDate", VDate)
        .component(Skeletor.name, Skeletor)

    await router.push(url)
    await router.isReady()

    await api('config').then(({data}) => {
        store.commit('setConfig', data)
    }).catch((e) => {})

    if (app.config.globalProperties.$route.meta.title) {
        store.commit('setTitle', app.config.globalProperties.$t(app.config.globalProperties.$route.meta.title))
    }

    const ctx = {
        modules: [],
    }

    const html = await renderToString(app)

    let preloadLinks = ''
    if (manifest) {
        renderPreloadLinks(ctx.modules, manifest)
    }

    const headPayload = await renderSSRHead(head)

    const {config, meta, post} = store.state
    const renderState = `
      <script>
        window.INITIAL_DATA = ${JSON.stringify({config, meta, post})}
      </script>`;

    return [html, preloadLinks, headPayload, renderState]
}

function renderPreloadLinks(modules, manifest) {
    let links = ''
    const seen = new Set()
    modules.forEach((id) => {
        const files = manifest[id]
        if (files) {
            files.forEach((file) => {
                if (!seen.has(file)) {
                    seen.add(file)
                    const filename = basename(file)
                    if (manifest[filename]) {
                        for (const depFile of manifest[filename]) {
                            links += renderPreloadLink(depFile)
                            seen.add(depFile)
                        }
                    }
                    links += renderPreloadLink(file)
                }
            })
        }
    })
    return links
}

function renderPreloadLink(file) {
    if (file.endsWith('.js')) {
        return `<link rel="modulepreload" crossorigin href="${file}">`
    } else if (file.endsWith('.css')) {
        return `<link rel="stylesheet" href="${file}">`
    } else if (file.endsWith('.woff')) {
        return ` <link rel="preload" href="${file}" as="font" type="font/woff" crossorigin>`
    } else if (file.endsWith('.woff2')) {
        return ` <link rel="preload" href="${file}" as="font" type="font/woff2" crossorigin>`
    } else if (file.endsWith('.gif')) {
        return ` <link rel="preload" href="${file}" as="image" type="image/gif">`
    } else if (file.endsWith('.jpg') || file.endsWith('.jpeg')) {
        return ` <link rel="preload" href="${file}" as="image" type="image/jpeg">`
    } else if (file.endsWith('.png')) {
        return ` <link rel="preload" href="${file}" as="image" type="image/png">`
    } else {
        return ''
    }
}