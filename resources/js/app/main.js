import { createApp } from "vue"
import { createI18n } from 'vue-i18n'
import App from "@/App.vue"
import { vue3Debounce } from 'vue-debounce'
import emitter from 'tiny-emitter/instance'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import isToday from 'dayjs/plugin/isToday'
import isYesterday from 'dayjs/plugin/isYesterday'
import isTomorrow from 'dayjs/plugin/isTomorrow'
import isSameOrAfter from 'dayjs/plugin/isSameOrAfter'
import VueSnip from 'vue-snip'
import messages from '@intlify/unplugin-vue-i18n/messages'
import { registerSW } from 'virtual:pwa-register'
import VueFullscreen from 'vue-fullscreen'
import 'dayjs/locale/ru'
import 'dayjs/locale/kk'
import router from "./router"
import { store, getCurrentLocale } from './store'
import InlineSvg from 'vue-inline-svg';
import VDate from '@/components/VDate.vue';
import AppLink from '@/components/AppLink.vue';
// import VueApexCharts from "vue3-apexcharts"
import { Skeletor } from 'vue-skeletor';
import { createHead, VueHeadMixin } from "@unhead/vue"
import { vMaska } from "maska"

// import * as Sentry from "@sentry/vue";
// import { BrowserTracing } from "@sentry/tracing";

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

dayjs.locale(getCurrentLocale())
dayjs.extend(isSameOrAfter)
dayjs.extend(relativeTime)
dayjs.extend(isToday)
dayjs.extend(isYesterday)
dayjs.extend(isTomorrow)

const app = createApp(App)
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

// if (import.meta.env.VITE_APP_ENV == 'production') {
    const updateSW = registerSW({
        onNeedRefresh() {
        // console.log('Need refresh')
        },
        onOfflineReady() {
        // console.log('Offline ready')
        }
    })
// }
  
const i18n = createI18n({
    legacy: true,
    locale: getCurrentLocale(),
    fallbackLocale: 'en',
    globalInjection: true,
    fallbackWarn: false,
    missingWarn: false,
    messages
})

// Sentry.init({
//   app,
//   dsn: "https://221da927d25943cfbf4751f1eda7bf0c@o1337152.ingest.sentry.io/4504102574227456",
//   integrations: [
//     new BrowserTracing({
//       routingInstrumentation: Sentry.vueRouterInstrumentation(router),
//       tracingOrigins: ["localhost", "tonex-app.webartisan.space", /^\//],
//     }),
//   ],
//   // Set tracesSampleRate to 1.0 to capture 100%
//   // of transactions for performance monitoring.
//   // We recommend adjusting this value in production
//   tracesSampleRate: 1.0,
// })

const head = createHead()

app
    .mixin(VueHeadMixin)
    .use(head)
    .use(i18n)
    .directive('debounce', vue3Debounce({
        lock: false,
        defaultTime: '300ms',
        listenTo: ['input']
    }))
    // .use(VueApexCharts)
    .use(VueSnip)
    .use(VueFullscreen)
    .use(router)
    .use(store)
    .directive("maska", vMaska)
    .component("inline-svg", InlineSvg)
    .component("app-link", AppLink)
    .component("VDate", VDate)
    .component(Skeletor.name, Skeletor)

export { app, router, store }
