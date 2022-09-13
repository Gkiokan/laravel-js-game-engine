import store from '~/store'

export default (to, from, next) => {
  if (store.getters['auth/user'] && store.getters['auth/user'].role !== 'mod') {
    next({ name: '403' })
  } else {
    next()
  }
}
