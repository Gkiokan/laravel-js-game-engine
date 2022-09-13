import store from '~/store'
import router from '~/router'
import config from '~/config'

export default async (to, from, next) => {
    // console.log('- middleware - auth redirect to login', (!store.getters['auth/check']) )

    if (!store.getters['auth/check']) {

      // let href = router.resolve(to).href
      // let auth = config.api.login
      // let go = auth + '?cb=' + href
      // window.location = go

      // route to the orginal routing list
      next({ name: 'login.req' })
    } else {
        let user = store.getters['auth/user']

        // console.log("Logged in user", user, user.gain_access)
        // allow only users with full access to proceed
        if(user.gain_access == 0){
          next({ name: 'login.notVerified' })
          store.dispatch('auth/logout')
        }
        else {
          next()
        }
    }
}
