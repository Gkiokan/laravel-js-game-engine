import { boot } from 'quasar/wrappers'
import axios from 'axios'
import store from '~/store'
import { Loading } from 'quasar'

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)

// Base
axios.defaults.baseURL = process.env.axiosBaseURL

// Request interceptor
axios.interceptors.request.use(request => {
    const token = store.getters['auth/token']
    if (token) {
      request.headers.common['Authorization'] = `Bearer ${token}`
    }

    const locale = store.getters['lang/locale']
    if (locale) {
      request.headers.common['Accept-Language'] = locale
    }

    // request.headers.common['X-Requested-With'] = 'XMLHttpRequest'
    request.headers.common['Content-Type'] = 'application/json'
    request.headers.common['Accept'] = 'application/json'

    // request.headers['X-Socket-Id'] = Echo.socketId()
    // console.log('request', request)
    Loading.show()

    return request
})

// Response interceptor
axios.interceptors.response.use(response =>
  {
      // console.log('response', response)
      Loading.hide()
      return response
  },

  error => {
      Loading.hide()

      if (typeof error.response === 'undefined') {
        console.log(axios.defaults.baseURL)
        console.log('A network error occurred. '
          + 'This could be a CORS issue or a dropped internet connection. '
          + 'It is not possible for us to know.')

        return Promise.reject(error)
      }

      const { status } = error.response
      const message = error.response.data.message

      if (status >= 500) {
        console.log(message)
        // alert(message)
        // swal.fire({
        //     title: status,
        //     text: message,
        //     reverseButtons: true,
        //     confirmButtonText: 'OK',
        //     cancelButtonText: 'Cancel',
        // })
      }

      if (status === 401 && store.getters['auth/check']) {
        alert(message)
        store.commit('auth/LOGOUT')
        router.push({ name: 'login.req' })
        // swal.warning({
        //     title: status,
        //     text: message,
        //     reverseButtons: true,
        //     confirmButtonText: 'OK',
        //     cancelButtonText: 'Cancel',
        // }).then(() => {
        //     store.commit('auth/LOGOUT')
        //     router.push({ name: 'login' })
        // })
      }

      return Promise.reject(error)
})

// Create api
const api = axios.create({ baseURL: process.env.axiosBaseURL })


// Export
export default boot(({ app }) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api

  app.config.globalProperties.$axios = axios
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
})

export { api }
