import store from '~/store'

export default async (to, from, next) => {
  // console.log('- middleware - running check out', store)
  // console.log('- check condition', (!store.getters['auth/check'] && store.getters['auth/token']) )
  if (!store.getters['auth/check'] && store.getters['auth/token']) {
    try {
      await store.dispatch('auth/fetchUser')
    } catch (e) { }
  }

  next()
}
