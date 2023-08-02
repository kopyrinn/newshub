import { app, router, store } from '@/app/main'
import { api, upload } from '@/app/api'
import { PushNotifications } from '@capacitor/push-notifications'
import { LocalNotifications } from '@capacitor/local-notifications'
import { Share } from '@capacitor/share'
import { Dialog } from '@capacitor/dialog'
import { StatusBar, Style } from '@capacitor/status-bar'
import { Clipboard } from '@capacitor/clipboard'
import { App } from '@capacitor/app'
import { Capacitor } from '@capacitor/core'
import { Browser } from '@capacitor/browser'
import { SafeAreaController } from '@aashu-dubey/capacitor-statusbar-safe-area';
import { registerSW } from 'virtual:pwa-register'

registerSW({ immediate: true })

const showAlert = async (title, message) => {
    await Dialog.alert({
        title: title,
        message: message,
    });
};

const registerPushNotifications = async () => {
    await PushNotifications.addListener('registration', token => {
        if (['ios', 'android'].includes(Capacitor.getPlatform())) {
            store.commit('setAppToken', token.value)
            store.commit('setPlatform', Capacitor.getPlatform())
        }
    })

    await PushNotifications.addListener('registrationError', err => {
        store.commit('setAppToken', '')
    })

    try {
        let permStatus = await PushNotifications.checkPermissions()

        if (permStatus.receive === 'prompt') {
            permStatus = await PushNotifications.requestPermissions()
        }

        if (permStatus.receive == 'granted') {
            PushNotifications.register()
        }
    } catch (e) {
        //
    }
}

const registerLocalNotifications = async () => {
    try {
        let permStatus = await LocalNotifications.checkPermissions()

        if (permStatus.display === 'prompt') {
            permStatus = await LocalNotifications.requestPermissions()
        }

        if (permStatus.display == 'granted') {
            await LocalNotifications.createChannel({
                id: 'all',
                name: 'All',
            })
        }
    } catch (e) {
        //
    }
}


app.config.globalProperties.$api = api
app.config.globalProperties.$get = (url, params = {}, isAuth = false) => {
    return api(url, isAuth, {params})
}
app.config.globalProperties.$post = (url, data = {}, isAuth = false) => {
    return api(url, isAuth, {method: 'POST', data})
}
app.config.globalProperties.$upload = upload
app.config.globalProperties.$isApp = true
app.config.globalProperties.$isWeb = false
app.config.globalProperties.$app = App
app.config.globalProperties.$capacitor = Capacitor
app.config.globalProperties.$share = Share
app.config.globalProperties.$statusBar = StatusBar
app.config.globalProperties.$statusBarStyle = Style
app.config.globalProperties.$dialog = showAlert
app.config.globalProperties.$clipboard = Clipboard
app.config.globalProperties.$browserOpen = async (url) => {
    await Browser.open({ url: url });
}

if (['ios', 'android'].includes(Capacitor.getPlatform())) {
    App.addListener('appUrlOpen', function (event) {
        const slug = event.url.split(import.meta.env.VITE_ORIGIN_DOMAIN).pop();

        if (slug) {
            router.push({
                path: slug,
            })
        }
    })

    SafeAreaController.injectCSSVariables()
    StatusBar.setOverlaysWebView({ overlay: true })

    registerPushNotifications()
    registerLocalNotifications()
}

router.isReady().then(() => app.mount('#kt_app_root'))
