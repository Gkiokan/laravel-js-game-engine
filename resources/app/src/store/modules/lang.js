import Cookies from 'js-cookie'
// import { Cookies } from 'quasar'

const { locale, locales } = window.config || { locale: 'de', locales: ['de', 'us'] }

// state
export const state = {
  locale: Cookies.get('locale') || locale,
  locales: locales
}

// getters
export const getters = {
  locale: state => state.locale,
  locales: state => state.locales
}

// mutations
export const mutations = {
  'SET_LOCALE' (state, { locale }) {
    state.locale = locale
  }
}

// actions
export const actions = {
  setLocale ({ commit }, { locale }) {
    commit('SET_LOCALE', { locale })

    Cookies.set('locale', locale, { expires: 365 })
  }
}
