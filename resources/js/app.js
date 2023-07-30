import { app, router, store } from '@/app/main'
import { api, upload } from '@/app/api'
import { registerSW } from 'virtual:pwa-register'
registerSW({ immediate: true })

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

const storeInitialState = window.INITIAL_DATA;
if (storeInitialState) {
  store.replaceState(Object.assign(store.state, storeInitialState));
}

store.commit('setTitle', '')

router.beforeEach((to, from, next) => {
    if (to.meta.title) {
        store.commit('setTitle', app.config.globalProperties.$t(to.meta.title))
    }

    next()
})

router.isReady().then(() => app.mount('#kt_app_root'))
