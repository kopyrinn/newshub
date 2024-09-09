import createPersistedState from "vuex-persistedstate"
import { createStore } from 'vuex'

const plugins = []

if (!import.meta.env.SSR) {
  plugins.push(createPersistedState())
}

export const store = createStore({
  state () {
    return {
      theme: 'system',
      menu: 'general',
      cacheKey: Math.random().toString(36).slice(2, 7),
      user: false,
      token: false,
      apptoken: false,
      platform: false,
      ref: '',
      config: {
        categories: [],
        users: [],
        postSlides: [],
        postFeatured: [],
        postLatest: [],
        rates: {
          USD: {},
          EUR: {},
          RUB: {},
        }
      },
      meta: {
        title: '',
      },
      feeds: [],
      post: {},
      paginate: {
        page: 1,
        lastPage: 0,
      }
    }
  },
  getters: {
    getConfig (state) {
        return state.config
    },
    getUser (state) {
        return !state.token? false: state.user
    },
    getToken (state) {
      return state.token
    },
    getTheme (state) {
      return state.theme
    },
    getMenu (state) {
      return state.menu
    },
    getMeta (state) {
      return state.meta
    },
    getTitle (state) {
      return state.meta.title
    },
    getLocale (state) {
      return getCurrentLocale()
    },
    getRef (state) {
      return state.ref
    },
    getFeeds (state) {
        return state.feeds
    },
    getAppToken (state) {
      return state.apptoken
    },
    getPlatform (state) {
      return state.platform
    },
    getPaginatePage (state) {
      return state.paginate.page
    },
    getPaginateLastPage (state) {
      return state.paginate.lastPage
    },
  },
  mutations: {
    updateCacheKey (state) {
      state.cacheKey = Math.random().toString(36).slice(2, 7)
    },
    setConfig (state, payload) {
      state.config = payload
    },
    setPost (state, payload) {
      state.post = payload
    },
    setToken (state, payload) {
      state.token = payload
    },
    setUser (state, payload) {
      state.user = payload
    },
    setTheme (state, payload) {
      state.theme = payload
    },
    setMenu (state, payload) {
      state.menu = payload
    },
    setTitle (state, payload) {
      state.meta.title = payload
    },
    setMeta (state, payload) {
      state.meta = payload
    },
    setRef (state, payload) {
      state.ref = payload
    },
    setFeeds (state, payload) {
      state.feeds = payload
    },
    addFeed (state, payload) {
      state.feeds.push(payload)
    },
    delFeed (state, payload) {
      var index = state.feeds.indexOf(payload);
      if (index !== -1) {
        state.feeds.splice(index, 1)
      }
    },
    setAppToken (state, payload) {
      state.apptoken = payload
    },
    setPlatform (state, payload) {
      state.platform = payload
    },
    setPaginatePage (state, payload) {
      state.paginate.page = payload
    },
    setPaginateLastPage (state, payload) {
      state.paginate.lastPage = payload
    },
  },
  plugins,
});

export const getDefaultLocale = () => {
  if (!import.meta.env.SSR) {
    const url = new URL(window.location.href)

    if (url.pathname.startsWith('/en/')) {
      return 'en'
    } else if (url.pathname.startsWith('/kk/')) {
      return 'kk'
    }
  } else if (global.url) {
    if (global.url.startsWith('/en/')) {
      return 'en'
    } else if (global.url.startsWith('/kk/')) {
      return 'kk'
    }
  }

  return 'ru'
}

export const getCurrentLocale = () => {
  let locale = getDefaultLocale()
  return locale
}
