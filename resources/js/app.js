import { app, router, store } from '@/app/main'
import { api, upload } from '@/app/api'
import { registerSW } from 'virtual:pwa-register'
import { PushNotifications } from '@capacitor/push-notifications'
import { FCM } from "@capacitor-community/fcm";
import { Share } from '@capacitor/share'
import { Dialog } from '@capacitor/dialog'
import { StatusBar, Style } from '@capacitor/status-bar'
import { Clipboard } from '@capacitor/clipboard'
import { App } from '@capacitor/app'
import { Capacitor } from '@capacitor/core'
import { Browser } from '@capacitor/browser'
import { SafeAreaController } from '@aashu-dubey/capacitor-statusbar-safe-area';
import { TextZoom } from "@capacitor/text-zoom"
import { Device } from '@capacitor/device'

registerSW({
    immediate: true,
    onRegisteredSW(_swUrl, registration) {
        registration?.update()
    },
})

const platform = Capacitor.getPlatform()

app.config.globalProperties.$isApp = ['ios', 'android'].includes(platform)
app.config.globalProperties.$isWeb = !['ios', 'android'].includes(platform)
app.config.globalProperties.$capacitor = Capacitor
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

router.beforeEach((to, from, next) => {
    if (to.meta.title) {
        store.commit('setTitle', app.config.globalProperties.$t(to.meta.title))
    }

    next()
})

if (['ios', 'android'].includes(platform)) {
    SafeAreaController.injectCSSVariables()
    StatusBar.setOverlaysWebView({ overlay: true })

    app.config.globalProperties.$app = App
    app.config.globalProperties.$share = Share
    app.config.globalProperties.$statusBar = StatusBar
    app.config.globalProperties.$statusBarStyle = Style
    app.config.globalProperties.$dialog = async (title, message) => {
        await Dialog.alert({
            title: title,
            message: message,
        });
    }
    app.config.globalProperties.$clipboard = Clipboard
    app.config.globalProperties.$browserOpen = async (url) => {
        await Browser.open({ url: url });
    }

    const registerPushNotifications = async () => {
        await PushNotifications.addListener('registration', token => {
            if (['ios', 'android'].includes(Capacitor.getPlatform())) {
                store.commit('setAppToken', token.value)
                store.commit('setPlatform', Capacitor.getPlatform())
            }
        })

        await PushNotifications.addListener('registrationError', err => {
            // showAlert('push', 'error')
            // console.log(err)
            store.commit('setAppToken', '')
        })

        try {
            let permStatus = await PushNotifications.checkPermissions()
            // showAlert('push', permStatus.receive)

            if (permStatus.receive === 'prompt') {
                permStatus = await PushNotifications.requestPermissions()
                // showAlert('push', 'requested')
            }

            if (permStatus.receive == 'granted') {
                await PushNotifications.register()

                await FCM.subscribeTo({ topic: "all" })
                    // .then((r) => showAlert('fcm', 'subscribed to topic'))
                    // .catch((err) => console.log(err))
            }
        } catch (e) {
            //
        }
    }

    registerPushNotifications()

    App.addListener('appUrlOpen', function (event) {
        // showAlert('url', event.url)
        const slug = event.url.split(import.meta.env.VITE_ORIGIN_DOMAIN).pop();

        if (slug) {
            router.push({
                path: slug,
            })
        }
    })

    const fixTextZoom = async () => {
        const info = await Device.getInfo()
    
        if (['ios', 'android'].includes(info.operatingSystem)) {
            TextZoom.set({
                value: 1.0
            })
        }
    };

    fixTextZoom();
}

router.isReady().then(() => app.mount('#kt_app_root'))
