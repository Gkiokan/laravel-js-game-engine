// import Vue from 'vue'
import { make } from 'vuex-pathify'
import Cookies from 'js-cookie'
// import { Cookies } from 'quasar'
import config from '~/config'
import { Screen } from 'quasar'

export const state = {
    ...config,
    animation: null,
    animationStopped: true,
    animationAutoStart: false,
    accept_privacy: () => {
        let value = localStorage.getItem('accept_privacy') // Cookies.get('accept_privacy')

        if(value && (value === false || value == "false"))
          return false

        return true
    },

    darkmode: () => {
        let value = localStorage.getItem('darkmode') // Cookies.get('darkmode')
        return value ? value : false
    },
}


// make all mutations
export const mutations = {
    ...make.mutations(state),

    setPrivacy(state, payload){
        let value = payload ? new Date().getTime() : false
        state.accept_privacy = value
        localStorage.setItem('accept_privacy', value)
        // Cookies.set('accept_privacy', value, {
        //       expires: 365,
        //       sameSite: 'lax',
        //       domain: window.location.host,
        //       secure: true,
        // })
    },

    setDarkmode(state, value){
        localStorage.setItem('darkmode', value)
        // Cookies.set('darkmode', value, {
        //       expires: 365,
        //       sameSite: 'lax',
        //       domain: window.location.host,
        //       secure: true,
        // })
    },
    enableAnalytics: () => Vue.$ga.enable(),
    disableAnalytics: () => Vue.$ga.disable(),
}

// actions
export const actions = {
    ...make.actions(state),

    // addFiles({ commit, dispatch, state}, payload){
    //     commit('addFiles', payload)
    // }

    setPrivacy({ dispatch, commit }, val){
        commit('setPrivacy', val)

        if(val) {
          // Vue.$ga.enable()
          commit('enableAnalytics')
          console.log(':: Enable Analytics')
        }
        else {
          // Vue.$ga.disable()
          commit('disableAnalytics')
          console.log(':: Disable Analytics')
        }
    },

    setDarkmode({ commit }, val){
        console.log('Change Darkmode to #' + val)
        commit('setDarkmode', val)
    }
}

// getters
export const getters = {
    // make all getters (optional)
    ...make.getters(state),

    // overwrite default `items` getter
    // allFiles: state => {
    //     return state.images
    // },

    getDarkmode: () => {
        let c = localStorage.getItem('darkmode') // Cookies.get('darkmode')

        if(!c)
          return false

        return (c === false || c == "false"|| !c) ? false : true
    },

    getPrivacy: state => {
        let s = state.accept_privacy
        let c = localStorage.getItem('accept_privacy') // Cookies.get('accept_privacy')

        if(!c)
          return new Date().getTime()

        if(c && (c === false || c == "false"))
          return false

        return c
    },

    isMobile(){
        return Screen.lt.sm
    },

    isTable(){
        return Screen.lt.md
    }
}

// console.log({
//     state, mutations, actions, getters
// })
