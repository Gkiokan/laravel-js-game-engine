import json from '~/../package.json'
// import Cookies from 'js-cookie'
import { Cookies } from 'quasar'

export default {
    gtm: '',
    ga: '',    
    debug: false,
    debug_ga: false,
    accept_privacy: false,

    appTitle: 'WCX',
    appName: 'WCX',
    version: json.version,

    showMenuButton: true,
    showDarwer: true,

    isProd: process.env.NODE_ENV === 'production',

    api: {
        login: '/login',
        register: '/register',
    },

    support: "",
}
