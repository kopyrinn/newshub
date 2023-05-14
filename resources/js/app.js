import { app, router, store } from '@/app/main'
import { api, upload } from '@/app/api'

app.config.globalProperties.$isApp = false
app.config.globalProperties.$isWeb = true
app.config.globalProperties.$api = api
app.config.globalProperties.$upload = upload

store.commit('setTitle', '')

router.beforeEach((to, from, next) => {
    if (to.meta.title) {
        store.commit('setTitle', app.config.globalProperties.$t(to.meta.title))
    }

    next()
})

router.isReady().then(() => app.mount('#kt_app_root'))
