import store from '~/store'

export default (to, from, next) => {
    if (store.getters['auth/user'] && store.getters['auth/user'].role !== 'admin') {
      next({ name: '403' })
    } else {
      next()
    }
}
