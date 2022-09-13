import store from '~/store'
import router from '~/router'
import config from '~/config'

export default async (to, from, next) => {
    // console.log('- middleware - auth redirect to login', (!store.getters['auth/check']) )

    let user = store.getters['auth/user']

    console.log("Verifed Middleware", user, user.gain_access)
    if (user.gain_access == 0) {

      // let href = router.resolve(to).href
      // let auth = config.api.login
      // let go = auth + '?cb=' + href
      // window.location = go

      // route to the orginal routing list
      console.log("User is not verified, jump to login")
      await store.dispatch('auth/logout')
      next({ name: 'login.notVerified' })
    } else {
      next()
    }
}
