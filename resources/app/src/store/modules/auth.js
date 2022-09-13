import axios from 'axios'
import Cookies from 'js-cookie'
// import { Cookies } from 'quasar'
import router from '~/router'

// state
export const state = {
    user: null,
    email: '',
    token: localStorage.getItem('token')
}

// getters
export const getters = {
    user: state => state.user,
    token: state => state.token,
    check: state => state.user !== null
}

// mutations
export const mutations = {
  'SAVE_TOKEN'(state, { token, remember }) {
      state.token = token
      // console.log('save token')
      localStorage.setItem('token', token)
      Cookies.set('token', token, {
            expires: remember ? 365 : null,
            sameSite: 'lax',
            domain: '.' + window.location.host,
            secure: true,
      })
  },

  'FETCH_USER_SUCCESS'(state, { user }) {
      state.user = user
  },

  'FETCH_USER_FAILURE'(state) {
      state.token = null
      localStorage.removeItem('token')
      Cookies.remove('token')
  },

  'LOGOUT'(state) {
      state.user = null
      state.token = null

      localStorage.removeItem('token')
      Cookies.remove('token', {
          path: '/',
          domain: '.' + window.location.host
      })
  },

  'UPDATE_USER'(state, { user }) {
      state.user = user
  }
}

// actions
export const actions = {
  saveToken ({ commit, dispatch }, payload) {
      commit('SAVE_TOKEN', payload)
  },

  async fetchUser ({ state, commit }) {

    try {
      // console.log('fetching user')
      const { data } = await axios.get('auth/user')

      commit('FETCH_USER_SUCCESS', { user: data.user })
    } catch (e) {
      commit('FETCH_USER_FAILURE')
    }
  },

  updateUser ({ commit }, payload) {
      commit('UPDATE_USER', payload)
  },


  async logout ({ commit }, cb = null) {
      try {
        await axios.post('/auth/logout')
      } catch (e) { }

      commit('LOGOUT')

      if( cb == null )
        router().push({ name: 'login' })

      if( typeof cb == "function")
        cb()
  },

  async fetchOauthUrl (ctx, { provider }) {
      const { data } = await axios.post(`/auth/oauth/${provider}`)

      return data.url
  }
}
