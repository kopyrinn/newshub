import axios from "axios";
import { computed } from 'vue'
import { store, getCurrentLocale } from './store'
// import imc from "image-minify-client"

export const api = async (endpoint = 'user', useAuth, config = {}) => {
    if (import.meta.env.SSR) {
        config.url = `http://127.0.0.1:8002/api/v2/${endpoint}`
    } else {
        config.url = `${import.meta.env.VITE_APP_URL}/api/v2/${endpoint}`
    }

    config.withCredentials = true

    if (!config.headers) {
        config.headers = {}
    }

    config.headers['Accept'] = 'application/json'
    config.headers['Locale'] = getCurrentLocale()

    if (useAuth) {
        const token = computed(() => store.state.token)
        config.headers['Authorization'] = `Bearer ${token.value}`
    }

    if (!import.meta.env.SSR) {
        return axios(config)
    } else {
        return await axios(config)
    }
};

export const upload = async (source, path) => {
    const serverUpload = async (file) => {
        const size = file.size  / (1024 ** 2)

        if (size >= 15) {
            return {
                ok: false,
                message: 'Maximum file size 15Mb'
            }
        }

        let formData = new FormData()
        formData.append('image', file)

        return await api(`image/${path}`, true, {
            method: 'post',
            headers: {
              'Content-Type': 'multipart/form-data'
            },
            data: formData
        })
        .then(({ data }) => {
            return data
        })
        .catch(({ response }) => {
            return response && response.data? response.data: false
        })
    }

    const uploader = async () => {
        const size = source.size  / (1024 ** 2)

        if (source.type == 'image/gif') {
            if (size >= 15) {
                return {
                    ok: false,
                    message: 'Maximum file size 15Mb'
                }
            }

            return await serverUpload(source)
        } else if (size <= 1) {
            return await serverUpload(source)
        }

        // return await imc(source, {
        //     quality: 1,
        //     maxWidth : 2000,
        //     maxHeight: 2000,
        //     outputType: 'webp'
        // }).then(async (rslt) => {
        //     // this.$math.round(100 - (rslt[0].size / rslt[1].size * 100)) + '% saved'

        //     return await serverUpload(rslt[0])
        // }).catch(async (e) => {
            return await serverUpload(source)
        // })
    }

    return await uploader()
}